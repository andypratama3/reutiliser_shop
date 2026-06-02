<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'revenue_today'    => Order::paid()->whereDate('paid_at', today())->sum('total_amount'),
            'revenue_month'    => Order::paid()->whereMonth('paid_at', now()->month)->sum('total_amount'),
            'orders_today'     => Order::whereDate('created_at', today())->count(),
            'pending_payment'  => Order::where('status', 'awaiting_payment')->count(),
            'total_products'   => Product::where('status', 'published')->count(),
            'total_customers'  => User::role('user')->count(),
            'low_stock'        => Product::whereRaw('stock <= low_stock_threshold')->count(),
        ];

        $recentOrders = Order::with(['user'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $revenueChart = Order::paid()
            ->selectRaw('DATE(paid_at) as date, SUM(total_amount) as total')
            ->where('paid_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'revenueChart'));
    }
}
