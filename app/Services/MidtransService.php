<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$clientKey    = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createTransaction(Order $order, string $paymentMethod, string $paymentChannel): Payment
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->recipient_name,
                'phone'      => $order->recipient_phone,
                'email'      => $order->user->email,
            ],
            'item_details' => $order->items->map(fn($item) => [
                'id'       => $item->product_id,
                'price'    => (int) $item->unit_price,
                'quantity' => $item->quantity,
                'name'     => $item->product_name,
            ])->toArray(),
        ];

        $params['enabled_payments'] = $this->resolvePaymentChannels($paymentMethod, $paymentChannel);

        $response = Snap::createTransaction($params);

        return Payment::create([
            'order_id'           => $order->id,
            'midtrans_order_id'  => $order->order_number,
            'payment_type'       => $this->mapPaymentType($paymentMethod),
            'payment_channel'    => $paymentChannel,
            'gross_amount'       => $order->total_amount,
            'status'             => 'pending',
            'expires_at'         => now()->addHours(24),
            'midtrans_response'  => (array) $response,
        ]);
    }

    public function verifySignature(array $payload, string $serverKey): bool
    {
        $expected = hash('sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $serverKey
        );
        return hash_equals($expected, $payload['signature_key'] ?? '');
    }

    private function resolvePaymentChannels(string $method, string $channel): array
    {
        return match ($method) {
            'va_bank'  => ['bca_va', 'bni_va', 'bri_va', 'mandiri_bill', 'permata_va'],
            'qris'     => ['qris'],
            'e_wallet' => ['gopay', 'ovo', 'dana', 'shopeepay'],
            default    => [],
        };
    }

    private function mapPaymentType(string $method): string
    {
        return match ($method) {
            'va_bank'  => 'bank_transfer',
            'qris'     => 'qris',
            'e_wallet' => 'gopay',
            default    => 'bank_transfer',
        };
    }
}
