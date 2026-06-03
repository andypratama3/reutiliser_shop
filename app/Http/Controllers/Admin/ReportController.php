<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SalesReportExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date',
            'payment_method' => 'nullable|string',
            'status'    => 'nullable|string',
        ]);

        $dateFrom      = $request->get('date_from');
        $dateTo        = $request->get('date_to');
        $paymentMethod = $request->get('payment_method');
        $status        = $request->get('status');

        $export = new SalesReportExport($dateFrom, $dateTo, $paymentMethod, $status);

        return Excel::download($export, 'laporan-penjualan-' . now()->format('Y-m-d') . '.xlsx');
    }
}
