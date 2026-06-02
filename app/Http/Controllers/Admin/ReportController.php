<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view reports');
    }

    public function index(Request $request)
    {
        $period = $request->get('period', 'month');

        $dateFrom = match ($period) {
            'week'  => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year'  => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $salesData = Order::paid()
            ->selectRaw('DATE(paid_at) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->where('paid_at', '>=', $dateFrom)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = $salesData->sum('total');
        $totalOrders  = $salesData->sum('count');

        $topProducts = Product::withCount(['orderItems as total_sold' => function ($q) use ($dateFrom) {
                $q->whereHas('order', fn($o) => $o->where('status', 'paid')
                    ->where('paid_at', '>=', $dateFrom));
            }])
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $paymentMethods = Order::paid()
            ->where('paid_at', '>=', $dateFrom)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();

        $dailySales = Order::paid()
            ->where('paid_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(paid_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.index', compact(
            'period', 'salesData', 'totalRevenue', 'totalOrders',
            'topProducts', 'paymentMethods', 'dailySales'
        ));
    }

    public function export(Request $request)
    {
        $request->validate(['period' => 'nullable|in:week,month,year']);

        return redirect()->route('admin.reports.index')
            ->with('info', 'Fitur export Excel akan segera tersedia.');
    }
}
