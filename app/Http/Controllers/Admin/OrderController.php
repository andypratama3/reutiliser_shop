<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly WhatsAppService $whatsApp)
    {
        $this->middleware('permission:manage orders');
    }

    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment', 'items'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(25)->withQueryString();

        $stats = [
            'total_today'    => Order::whereDate('created_at', today())->count(),
            'pending_payment'=> Order::where('status', 'awaiting_payment')->count(),
            'total_paid'     => Order::paid()->count(),
            'revenue_today'  => Order::paid()->whereDate('paid_at', today())->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment', 'shipment', 'promoCode']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,shipped,delivered,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        if ($request->status === 'shipped') {
            $request->validate([
                'tracking_number' => 'required|string',
                'courier'         => 'required|string',
            ]);
            $order->shipment()->updateOrCreate(
                ['order_id' => $order->id],
                [
                    'courier'         => $request->courier,
                    'tracking_number' => $request->tracking_number,
                    'status'          => 'in_transit',
                    'shipped_at'      => now(),
                ]
            );
            $this->whatsApp->sendShippingUpdate($order);
        }

        return back()->with('success', 'Status order diperbarui.');
    }
}
