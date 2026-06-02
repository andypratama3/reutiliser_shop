<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWaitlistNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Product $product) {}

    public function handle(WhatsAppService $whatsApp): void
    {
        $waitlists = $this->product->waitlists()
            ->where('notified', false)
            ->get();

        foreach ($waitlists as $waitlist) {
            $phone = $waitlist->phone ?? $waitlist->user?->phone;
            if (!$phone) continue;

            $message = "🎉 *Kabar Baik!*\n\n";
            $message .= "Produk *{$this->product->name}* yang kamu tunggu sudah tersedia kembali!\n\n";
            $message .= "Segera order sebelum kehabisan: " . route('products.show', $this->product->slug);

            $whatsApp->send($phone, $message, null, 'waitlist_notification');
            $waitlist->update(['notified' => true, 'notified_at' => now()]);
        }
    }
}
