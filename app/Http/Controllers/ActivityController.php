<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'diproses');
        $orders = Order::when($tab === 'selesai', fn ($q) => $q->whereIn('status', ['selesai', 'diambil']), fn ($q) => $q->where('status', 'diproses'))
            ->when($request->search, fn ($q, $search) => $q->where(fn ($sub) => $sub->where('customer_name', 'like', "%$search%")->orWhere('order_code', 'like', "%$search%")))
            ->latest()->paginate(15)->withQueryString();
        return view('aktivitas.index', compact('orders', 'tab'));
    }
}
