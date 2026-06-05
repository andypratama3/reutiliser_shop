<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // If shipping, validate tracking fields BEFORE updating status
        if ($request->status === 'shipped') {
            $request->validate([
                'tracking_number' => 'required|string',
                'courier'         => 'required|string',
            ]);
        }

        // Handle cancellation: restore stock (admin can cancel orders in wider range of statuses)
        if ($request->status === 'cancelled') {
            $cancellableStatuses = ['pending', 'awaiting_payment', 'paid', 'processing'];
            if (!in_array($order->status, $cancellableStatuses)) {
                return back()->with('error', 'Order tidak bisa dibatalkan pada status ini (sudah dikirim/selesai).');
            }

            DB::transaction(function () use ($order) {
                $wasPaid = in_array($order->status, ['paid', 'processing']);
                $order->update(['status' => 'cancelled']);
                $order->load('items.product', 'items.variant');

                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                        // If the order was already paid, also decrement sold_count
                        if ($wasPaid) {
                            $item->product->decrement('sold_count', min($item->quantity, $item->product->sold_count));
                        }
                    }
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }
            });

            return back()->with('success', 'Order dibatalkan dan stok dikembalikan.');
        }

        $order->update(['status' => $request->status]);

        if ($request->status === 'shipped') {
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

        if ($request->status === 'delivered') {
            $order->shipment()->update([
                'status'       => 'delivered',
                'delivered_at' => now(),
            ]);
        }

        return back()->with('success', 'Status order diperbarui.');
    }
}
