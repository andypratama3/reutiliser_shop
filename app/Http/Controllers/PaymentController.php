<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct(
        private readonly WhatsAppService $whatsApp,
        private readonly \App\Services\MidtransService $midtransService
    ) {}

    public function webhook(Request $request)
    {
        if ($request->isMethod('GET')) {
            return response()->json(['message' => 'OK'], 200);
        }

        $payload = $request->all();
        $midtransOrderId = $payload['order_id'] ?? null;

        // Check if order_id is missing in the payload
        if (!$midtransOrderId) {
            return response()->json(['message' => 'Callback received, but order_id missing'], 200);
        }

        // Check if it's a test notification from Midtrans Dashboard
        if (str_contains($midtransOrderId, 'payment_notif_test')) {
            Log::info('Midtrans test notification received and ignored: ' . $midtransOrderId);
            return response()->json(['message' => 'OK (Test)'], 200);
        }

        Log::info('Midtrans webhook received', $payload);

        $serverKey = config('midtrans.server_key') ?: config('services.midtrans.server_key');
        
        if (!$this->midtransService->verifySignature($payload, $serverKey)) {
            Log::warning('Midtrans invalid signature', ['order_id' => $midtransOrderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Find the payment record by the Midtrans Order ID (which might have -T suffix)
        $payment = Payment::where('midtrans_order_id', $midtransOrderId)->first();
        
        if (!$payment) {
            // Fallback: try searching for original order_number if it's a direct match
            $order = Order::where('order_number', $midtransOrderId)->first();
            if ($order) {
                $payment = Payment::where('order_id', $order->id)->first();
            }
        }

        if (!$payment || !$payment->order) {
            Log::error('Order or Payment not found for Midtrans ID: ' . $midtransOrderId);
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order = $payment->order;

        $transactionStatus = $payload['transaction_status'];
        $fraudStatus       = $payload['fraud_status'] ?? 'accept';

        $payment->update([
            'transaction_id'   => $payload['transaction_id'] ?? $payment->transaction_id,
            'status'           => $transactionStatus,
            'fraud_status'     => $fraudStatus,
            'settlement_time'  => $payload['settlement_time'] ?? $payment->settlement_time,
            'midtrans_response'=> $payload,
        ]);

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $order->update(['status' => 'pending']);
            } else if ($fraudStatus == 'accept') {
                $this->finalizePayment($order);
            }
        } else if ($transactionStatus == 'settlement') {
            $this->finalizePayment($order);
        } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $this->cancelOrder($order);
        } else if ($transactionStatus == 'pending') {
            $order->update(['status' => 'awaiting_payment']);
        }

        return response()->json(['message' => 'OK'], 200);
    }

    private function finalizePayment(Order $order)
    {
        if ($order->isPaid()) {
            return;
        }

        DB::transaction(function () use ($order) {
            $order->markAsPaid();
            $order->update(['status' => 'processing']);
            $order->load('items');

            foreach ($order->items as $item) {
                $item->product?->increment('sold_count', $item->quantity);
            }
        });

        try {
            $this->whatsApp->sendOrderConfirmation($order);
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }

        Log::info('Order paid and finalized: ' . $order->order_number);
    }

    private function cancelOrder(Order $order)
    {
        if ($order->status === 'cancelled') {
            return;
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);
            $order->load('items');

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
                if ($item->variant) {
                    $item->variant->increment('stock', $item->quantity);
                }
            }
        });

        try {
            $this->whatsApp->sendPaymentFailed($order);
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }

        Log::info('Order cancelled and stock restored: ' . $order->order_number);
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
