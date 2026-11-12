<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'week');
        [$from, $to] = match ($period) {
            'today' => [today(), today()],
            'month' => [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()],
            default => [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()],
        };
        $orders = Order::whereBetween('entry_date', [$from, $to]);
        $revenue = Order::whereBetween('entry_date', [$from, $to])->whereIn('status', ['selesai', 'diambil'])->sum('total_price');
        $daily = Order::select('entry_date', DB::raw('SUM(CASE WHEN status IN ("selesai", "diambil") THEN total_price ELSE 0 END) as revenue'))
            ->whereBetween('entry_date', [now()->subDays(6)->toDateString(), today()->toDateString()])->groupBy('entry_date')->orderBy('entry_date')->get();
        $statuses = Order::select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total', 'status');

        return view('dashboard.index', [
            'period' => $period,
            'todayOrders' => Order::whereDate('entry_date', today())->count(),
            'todayRevenue' => Order::whereDate('entry_date', today())->whereIn('status', ['selesai', 'diambil'])->sum('total_price'),
            'processingCount' => Order::where('status', 'diproses')->count(),
            'completedCount' => Order::where('status', 'selesai')->count(),
            'periodOrders' => $orders->count(), 'periodRevenue' => $revenue,
            'latestOrders' => Order::latest()->take(5)->get(),
            'chartLabels' => $daily->map(fn ($row) => Carbon::parse($row->entry_date)->format('d/m'))->values(),
            'chartValues' => $daily->pluck('revenue')->values(),
            'statusLabels' => $statuses->keys()->values(), 'statusValues' => $statuses->values(),
        ]);
    }
}
