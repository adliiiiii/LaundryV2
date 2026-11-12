<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('items')->when($request->search, fn ($q, $search) => $q->where(fn ($query) => $query->where('order_code', 'like', "%$search%")->orWhere('customer_name', 'like', "%$search%")))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))->latest()->paginate(15)->withQueryString();
        return view('orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $items = Item::with('category')->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))->orderBy('name')->get();

        $services = $items->map(fn (Item $item) => [
            'id' => $item->id,
            'name' => $item->name,
            'category' => $item->category->name,
            'price' => (float) $item->price,
            'unit' => $item->unit,
            'days' => $item->category->estimasi_hari,
        ])->values();

        return view('orders.create', [
            'items' => $items,
            'servicesJson' => $services->toJson(),
            'oldItemsJson' => collect(old('items', []))->map(fn ($item) => [
                'item_id' => data_get($item, 'item_id', ''),
                'qty' => data_get($item, 'qty', '1'),
            ])->values()->toJson(),
        ]);
    }

    public function store(OrderRequest $request)
    {
        $data = $request->validated();
        $itemIds = collect($data['items'])->pluck('item_id');
        $items = Item::with('category')->whereIn('id', $itemIds)->where('is_active', true)->whereHas('category', fn ($query) => $query->where('is_active', true))->get()->keyBy('id');
        if ($items->count() !== $itemIds->unique()->count()) return back()->withErrors(['items' => 'Item tidak tersedia atau nonaktif.'])->withInput();

        $finish = collect($data['items'])->map(fn ($row) => \Carbon\Carbon::parse($data['entry_date'])->addDays($items[$row['item_id']]->category->estimasi_hari))->max();
        $order = DB::transaction(function () use ($data, $itemIds, $finish) {
            $items = Item::with('category')->whereIn('id', $itemIds)->where('is_active', true)
                ->whereHas('category', fn ($query) => $query->where('is_active', true))->lockForUpdate()->get()->keyBy('id');
            if ($items->count() !== $itemIds->unique()->count()) {
                throw new \RuntimeException('Item tidak tersedia atau nonaktif. Silakan ulangi pembuatan order.');
            }
            $order = Order::create([
                'order_code' => 'TEMP-'.bin2hex(random_bytes(8)), 'customer_name' => $data['customer_name'], 'customer_phone' => $data['customer_phone'],
                'total_price' => 0, 'entry_date' => $data['entry_date'], 'estimated_finish_date' => $finish, 'status' => 'diproses', 'notes' => $data['notes'] ?? null,
            ]);
            $total = 0;
            foreach ($data['items'] as $row) {
                $item = $items[$row['item_id']];
                $qty = (float) $row['qty'];
                $subtotal = round((float) $item->price * $qty, 2);
                $order->items()->create(['item_id' => $item->id, 'item_name' => $item->name, 'item_price' => $item->price, 'qty' => $qty, 'unit' => $item->unit, 'subtotal' => $subtotal]);
                $total += $subtotal;
            }
            $order->update(['total_price' => $total, 'order_code' => sprintf('LDR-%s-%04d', now()->format('Ymd'), $order->id)]);
            return $order;
        });
        return redirect()->route('orders.show', $order)->with('success', 'Order berhasil dibuat.');
    }

    public function show(Order $order) { $order->load('items'); return view('orders.show', compact('order')); }
    public function nota(Order $order) { $order->load('items'); return view('orders.nota', compact('order')); }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate(['status' => ['required', Rule::in(Order::STATUSES)]]);
        $order->update($data);
        return back()->with('success', 'Status order diperbarui.');
    }
}
