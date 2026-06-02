<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private readonly WhatsAppService $whatsApp) {}

    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans webhook received', $payload);

        $serverKey    = config('midtrans.server_key') ?: config('services.midtrans.server_key');
        $signatureKey = hash('sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $serverKey
        );

        if ($signatureKey !== $payload['signature_key']) {
            Log::warning('Midtrans invalid signature', ['order_id' => $payload['order_id']]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $payload['order_id'])->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment = Payment::where('order_id', $order->id)->first();
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->update([
            'transaction_id'   => $payload['transaction_id'] ?? null,
            'status'           => $payload['transaction_status'],
            'fraud_status'     => $payload['fraud_status'] ?? null,
            'settlement_time'  => $payload['settlement_time'] ?? null,
            'midtrans_response'=> $payload,
        ]);

        $isSettled = in_array($payload['transaction_status'], ['settlement', 'capture'])
            && ($payload['fraud_status'] ?? 'accept') === 'accept';

        if ($isSettled && !$order->isPaid()) {
            $order->markAsPaid();
            $order->update(['status' => 'processing']);
            $order->load('items');

            foreach ($order->items as $item) {
                $item->product?->increment('sold_count', $item->quantity);
            }

            $this->whatsApp->sendOrderConfirmation($order);

            Log::info('Order paid: ' . $order->order_number);
        }

        if (in_array($payload['transaction_status'], ['deny', 'cancel', 'expire', 'failure'])) {
            $order->update(['status' => 'cancelled']);
            $this->whatsApp->sendPaymentFailed($order);
        }

        return response()->json(['message' => 'OK'], 200);
    }

    public function status(Order $order)
    {
        $this->authorize('view', $order);
        return response()->json([
            'order_number' => $order->order_number,
            'status'       => $order->status,
            'paid_at'      => $order->paid_at,
            'payment'      => $order->payment?->only(['status', 'va_number', 'qr_code_url', 'expires_at']),
        ]);
    }
}
