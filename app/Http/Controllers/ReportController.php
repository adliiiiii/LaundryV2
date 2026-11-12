<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private function dates(Request $request): array
    {
        $type = $request->query('type', 'month');
        if ($type === 'day') { $date = Carbon::parse($request->query('date', today()->toDateString())); return [$date->toDateString(), $date->toDateString()]; }
        if ($type === 'week') {
            $week = $request->query('week', now()->format('o-\WW'));
            if (preg_match('/^(\d{4})-W(\d{2})$/', $week, $matches)) {
                $date = Carbon::now()->setISODate((int) $matches[1], (int) $matches[2]);
            } else {
                $date = Carbon::parse($week);
            }
            return [$date->copy()->startOfWeek()->toDateString(), $date->copy()->endOfWeek()->toDateString()];
        }
        $month = Carbon::createFromFormat('Y-m', $request->query('month', now()->format('Y-m')));
        return [$month->copy()->startOfMonth()->toDateString(), $month->endOfMonth()->toDateString()];
    }

    public function index(Request $request)
    {
        [$from, $to] = $this->dates($request);
        $rows = Order::whereBetween('entry_date', [$from, $to])->selectRaw('DATE(entry_date) as tanggal, COUNT(*) as jumlah_order, SUM(total_price) as pendapatan')->groupBy(DB::raw('DATE(entry_date)'))->orderBy('tanggal')->get();
        $details = DB::table('order_items')->join('orders', 'orders.id', '=', 'order_items.order_id')->leftJoin('items', 'items.id', '=', 'order_items.item_id')->leftJoin('categories', 'categories.id', '=', 'items.category_id')
            ->whereBetween('orders.entry_date', [$from, $to])->selectRaw('DATE(orders.entry_date) as tanggal, categories.name as kategori, SUM(order_items.subtotal) as pendapatan')->groupBy(DB::raw('DATE(orders.entry_date)'), 'categories.name')->get()->groupBy('tanggal');
        return view('reports.index', ['rows' => $rows, 'details' => $details, 'from' => $from, 'to' => $to, 'type' => $request->query('type', 'month'), 'orderTotal' => $rows->sum('jumlah_order'), 'revenueTotal' => $rows->sum('pendapatan')]);
    }

    public function export(Request $request)
    {
        [$from, $to] = $this->dates($request);
        return Excel::download(new ReportExport($from, $to), 'rekapan-laundry-'.$from.'-'.$to.'.xlsx');
    }
}
