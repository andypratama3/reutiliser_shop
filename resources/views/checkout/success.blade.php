@extends('layouts.app')
@section('title', 'Pesanan Berhasil')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 text-center">
    <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-on-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>
    </div>

    <h1 class="font-headline text-3xl text-primary mb-4">Pesanan Berhasil Dibuat!</h1>
    <p class="font-body-lg text-body-lg text-on-surface-variant mb-8">
        Terima kasih! Pesanan kamu telah berhasil dibuat.
    </p>

    <div class="bg-surface border border-outline-variant p-8 text-left mb-8">
        <div class="mb-6">
            <p class="font-label-caps text-label-caps text-on-surface-variant tracking-wider mb-1">NOMOR PESANAN</p>
            <p class="font-headline text-2xl text-primary">{{ $order->order_number }}</p>
        </div>

        <div class="mb-6">
            <p class="font-label-caps text-label-caps text-on-surface-variant tracking-wider mb-1">TOTAL PEMBAYARAN</p>
            <p class="font-headline text-2xl text-on-surface">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
        </div>

        <div class="mb-6">
            <p class="font-label-caps text-label-caps text-on-surface-variant tracking-wider mb-2">METODE PEMBAYARAN</p>
            <p class="font-body-md text-body-md text-on-surface">
                @switch($order->payment_method)
                    @case('va_bank')
                        Transfer Virtual Account {{ $order->payment_channel }}
                        @if($order->payment && $order->payment->va_number)
                            <br><span class="font-semibold">No. VA: {{ $order->payment->va_number }}</span>
                        @endif
                        @break
                    @case('qris')
                        QRIS
                        @break
                    @case('e_wallet')
                        {{ $order->payment_channel }}
                        @break
                @endswitch
            </p>
        </div>

        <div>
            <p class="font-label-caps text-label-caps text-on-surface-variant tracking-wider mb-2">INSTRUKSI PEMBAYARAN</p>
            <p class="font-body-md text-body-md text-on-surface-variant">
                Instruksi pembayaran telah dikirim melalui WhatsApp ke nomor <strong>{{ $order->recipient_phone }}</strong>.
                Silakan lakukan pembayaran sebelum batas waktu yang ditentukan.
            </p>
        </div>

        @if($order->payment && $order->payment->expires_at)
            <div class="mt-6 p-4 bg-surface-container-low border border-outline-variant">
                <p class="font-label-caps text-label-caps text-on-surface-variant tracking-wider mb-1">BATAS WAKTU PEMBAYARAN</p>
                <p class="font-headline text-xl text-error">{{ $order->payment->expires_at->format('d M Y H:i') }}</p>
            </div>
        @endif
    </div>

    <div class="space-y-3">
        <a href="{{ route('products.index') }}"
           class="inline-block w-full bg-primary text-on-primary py-4 font-label-caps text-label-caps tracking-wider hover:opacity-90 transition-opacity">
            Lanjut Belanja
        </a>
        <a href="{{ route('account.orders.index') }}"
           class="inline-block w-full py-4 font-label-caps text-label-caps text-on-surface-variant border border-outline-variant hover:bg-surface-variant transition-colors">
            Lihat Pesanan Saya
        </a>
    </div>
</div>
@endsection
