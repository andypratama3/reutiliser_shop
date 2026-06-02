@extends('layouts.app')

@section('title', 'Pesanan ' . $order->order_number . ' | RÉUTILISER')

@section('content')
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <a href="{{ route('account.orders') }}" class="inline-flex items-center gap-2 font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors mb-6">
        <span class="material-symbols-outlined">arrow_back</span>
        Kembali ke Pesanan
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface border border-outline-variant p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="font-headline-md text-headline-md text-primary">Pesanan</h1>
                        <p class="font-headline-md text-headline-md text-on-background">{{ $order->order_number }}</p>
                    </div>
                    @php
                        $statusLabels = [
                            'pending' => 'Pending',
                            'awaiting_payment' => 'Menunggu Pembayaran',
                            'paid' => 'Dibayar',
                            'processing' => 'Diproses',
                            'shipped' => 'Dikirim',
                            'delivered' => 'Diterima',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                            'refunded' => 'Refund',
                        ];
                        $statusColors = [
                            'pending' => 'bg-surface-variant text-on-surface-variant',
                            'awaiting_payment' => 'bg-yellow-100 text-yellow-800',
                            'paid' => 'bg-blue-100 text-blue-800',
                            'processing' => 'bg-indigo-100 text-indigo-800',
                            'shipped' => 'bg-purple-100 text-purple-800',
                            'delivered' => 'bg-green-100 text-green-800',
                            'completed' => 'bg-green-200 text-green-900',
                            'cancelled' => 'bg-red-100 text-red-800',
                            'refunded' => 'bg-orange-100 text-orange-800',
                        ];
                    @endphp
                    <span class="px-4 py-2 font-label-caps text-label-caps {{ $statusColors[$order->status] ?? 'bg-surface-variant text-on-surface-variant' }}">
                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                    </span>
                </div>
                <p class="font-body-md text-body-md text-on-surface-variant">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>

            @if($order->payment && $order->payment->va_number)
            <div class="bg-surface border border-outline-variant p-6">
                <h2 class="font-label-caps text-label-caps text-primary font-bold mb-3">Pembayaran</h2>
                <p class="font-body-md text-body-md">Virtual Account: <strong>{{ $order->payment->va_number }}</strong></p>
                <p class="font-body-md text-body-md text-on-surface-variant">Bank: {{ $order->payment->payment_channel }}</p>
                @if($order->payment->expires_at)
                    <p class="font-body-md text-body-md text-on-surface-variant">Berlaku hingga: {{ $order->payment->expires_at->format('d M Y, H:i') }}</p>
                @endif
            </div>
            @endif

            @if($order->shipment)
            <div class="bg-surface border border-outline-variant p-6">
                <h2 class="font-label-caps text-label-caps text-primary font-bold mb-3">Pengiriman</h2>
                <p class="font-body-md text-body-md">Kurir: {{ $order->shipment->courier ?? '-' }}</p>
                <p class="font-body-md text-body-md">Resi: {{ $order->shipment->tracking_number ?? '-' }}</p>
            </div>
            @endif

            <div class="bg-surface border border-outline-variant p-6">
                <h2 class="font-label-caps text-label-caps text-primary font-bold mb-4">Item Pesanan</h2>
                <div class="divide-y divide-outline-variant">
                    @foreach($order->items as $item)
                        <div class="flex gap-4 py-4">
                            <div class="w-16 h-20 bg-secondary-container flex-shrink-0"></div>
                            <div class="flex-1">
                                <p class="font-body-md text-body-md font-semibold">{{ $item->product_name }}</p>
                                @if($item->variant_info)
                                    <p class="font-label-caps text-label-caps text-on-surface-variant">{{ $item->variant_info }}</p>
                                @endif
                                <p class="font-body-md text-body-md text-on-surface-variant">{{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-body-md text-body-md font-semibold">Rp {{ number_format($item->total_price, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-surface border border-outline-variant p-6">
                <h2 class="font-label-caps text-label-caps text-primary font-bold mb-4">Ringkasan</h2>
                <div class="space-y-3 font-body-md text-body-md">
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-primary">
                            <span>Diskon</span>
                            <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-headline-md pt-3 border-t border-outline-variant">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-surface border border-outline-variant p-6">
                <h2 class="font-label-caps text-label-caps text-primary font-bold mb-3">Alamat Pengiriman</h2>
                <p class="font-body-md text-body-md font-semibold">{{ $order->recipient_name }}</p>
                <p class="font-body-md text-body-md text-on-surface-variant">{{ $order->recipient_phone }}</p>
                <p class="font-body-md text-body-md text-on-surface-variant">{{ $order->shipping_address }}</p>
                <p class="font-body-md text-body-md text-on-surface-variant">{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
