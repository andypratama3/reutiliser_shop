@extends('layouts.app')

@section('title', 'Pesanan Saya | RÉUTILISER')

@section('content')
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-8">
    <h1 class="font-headline-md text-headline-md text-primary mb-8">Pesanan Saya</h1>

    @if($orders->isEmpty())
        <div class="text-center py-16 border border-dashed border-outline-variant">
            <span class="material-symbols-outlined text-6xl text-outline mb-4">inbox</span>
            <p class="font-body-md text-body-md text-on-surface-variant">Belum ada pesanan.</p>
            <a href="{{ route('products.index') }}" class="inline-block mt-4 bg-primary text-on-primary px-8 py-3 font-label-caps text-label-caps hover:opacity-90 transition-opacity">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <a href="{{ route('account.orders.show', $order) }}" class="block bg-surface border border-outline-variant p-6 hover:border-primary transition-colors group">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-body-md text-body-md text-on-surface-variant">Order</p>
                            <p class="font-headline-md text-headline-md text-primary group-hover:opacity-70 transition-opacity">{{ $order->order_number }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-body-md text-body-md font-semibold text-on-background">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <p class="font-label-caps text-label-caps text-on-surface-variant">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex gap-2 flex-wrap">
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
                            <span class="px-3 py-1 font-label-caps text-label-caps {{ $statusColors[$order->status] ?? 'bg-surface-variant text-on-surface-variant' }}">
                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</span>
                    </div>
                    @if($order->items->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-outline-variant">
                            <p class="font-label-caps text-label-caps text-on-surface-variant">{{ $order->items->count() }} item</p>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
