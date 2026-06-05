<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $apiUrl;
    private string $token;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.url', 'https://api.fonnte.com/send');
        $this->token  = config('services.whatsapp.token');
    }

    public function sendPaymentInstruction(Order $order, Payment $payment): void
    {
        $phone = $order->recipient_phone;

        $message = "Halo *{$order->recipient_name}*! 👋\n\n";
        $message .= "Order kamu *#{$order->order_number}* sudah dibuat.\n\n";
        $message .= "💳 *Detail Pembayaran:*\n";

        if ($payment->va_number) {
            $message .= "No. Virtual Account: *{$payment->va_number}*\n";
            $message .= "Bank: *{$payment->payment_channel}*\n";
        } elseif ($payment->qr_code_url) {
            $message .= "Scan QR Code: {$payment->qr_code_url}\n";
        }

        $message .= "Total: *Rp " . number_format($order->total_amount, 0, ',', '.') . "*\n";
        $message .= "Berlaku hingga: *" . $payment->expires_at?->format('d/m/Y H:i') . "*\n\n";
        $message .= "Segera lakukan pembayaran sebelum expired. Terima kasih! 🙏";

        $this->send($phone, $message, $order->id, 'payment_reminder', $order->user_id);
    }

    public function sendOrderConfirmation(Order $order): void
    {
        $phone   = $order->recipient_phone;
        $message = "✅ *Pembayaran Diterima!*\n\n";
        $message .= "Halo *{$order->recipient_name}*, pembayaran order *#{$order->order_number}* sudah dikonfirmasi.\n\n";
        $message .= "📦 *Pesananmu sedang diproses* dan akan segera dikirim.\n\n";
        $message .= "Total: *Rp " . number_format($order->total_amount, 0, ',', '.') . "*\n\n";
        $message .= "Terima kasih sudah belanja! 🛍️";

        $this->send($phone, $message, $order->id, 'order_confirmed', $order->user_id);
    }

    public function sendPaymentFailed(Order $order): void
    {
        $phone   = $order->recipient_phone;
        $message = "❌ *Pembayaran Gagal*\n\n";
        $message .= "Halo *{$order->recipient_name}*, sayang sekali pembayaran untuk order *#{$order->order_number}* gagal/kadaluarsa.\n\n";
        $message .= "Silakan buat order baru atau hubungi kami jika ada pertanyaan.";

        $this->send($phone, $message, $order->id, 'payment_failed', $order->user_id);
    }

    public function sendShippingUpdate(Order $order): void
    {
        $shipment = $order->shipment;
        $phone    = $order->recipient_phone;
        $message  = "🚚 *Pesanan Sedang Dikirim!*\n\n";
        $message  .= "Halo *{$order->recipient_name}*, order *#{$order->order_number}* sudah dikirim.\n\n";
        $message  .= "Kurir: *{$shipment->courier}*\n";
        $message  .= "No. Resi: *{$shipment->tracking_number}*\n\n";
        $message  .= "Cek status pengiriman di website kurir ya! 📍";

        $this->send($phone, $message, $order->id, 'shipping_update', $order->user_id);
    }

    public function send(string $phone, string $message, ?int $orderId = null, string $type = 'general', ?int $userId = null): void
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout(10)
                ->post($this->apiUrl, [
                    'target'  => $phone,
                    'message' => $message,
                ]);

            NotificationLog::create([
                'user_id'   => $userId,
                'order_id'  => $orderId,
                'channel'   => 'whatsapp',
                'recipient' => $phone,
                'type'      => $type,
                'message'   => $message,
                'status'    => $response->successful() ? 'sent' : 'failed',
                'sent_at'   => now(),
            ]);

        } catch (\Exception $e) {
            Log::error("WhatsApp send failed: {$e->getMessage()}", compact('phone', 'type'));
            NotificationLog::create([
                'user_id'       => $userId,
                'order_id'      => $orderId,
                'channel'       => 'whatsapp',
                'recipient'     => $phone,
                'type'          => $type,
                'message'       => $message,
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
