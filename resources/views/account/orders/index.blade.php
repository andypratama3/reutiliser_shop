@extends('layouts.landing')

@section('title', 'Your Archive | RÉUTILISER')

@section('content')
<main class="max-w-[1440px] mx-auto px-8 md:px-16 py-12">
    <!-- Page Header -->
    <div class="mb-20 reveal-item text-center md:text-left">
        <h1 class="font-display-lg text-primary mb-4 uppercase tracking-tighter">Your Archive</h1>
        <p class="font-body-md text-secondary max-w-lg mx-auto md:mx-0 text-lg opacity-60">A curated history of your conscious acquisitions and their journey through the circular economy.</p>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-32 reveal-item">
            <span class="material-symbols-outlined text-6xl text-secondary opacity-20 mb-8">history</span>
            <p class="font-body-lg text-secondary italic mb-12">Your archival history is currently empty.</p>
            <a href="{{ route('shop') }}"
               class="inline-block bg-primary text-white px-12 py-6 rounded-full font-label-caps text-[11px] tracking-widest hover:bg-primary-container transition-all shadow-xl uppercase">
                Explore Archives
            </a>
        </div>
    @else
        <div class="space-y-8 reveal-item">
            @foreach($orders as $order)
                <a href="{{ route('account.orders.show', $order) }}" class="block group bg-white p-8 md:p-12 rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all border border-primary/5">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                        <div class="flex-grow">
                            <div class="flex items-center gap-4 mb-4">
                                @php
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'awaiting_payment' => 'Awaiting Payment',
                                        'paid' => 'Reserved',
                                        'processing' => 'In Reconstruction',
                                        'shipped' => 'In Transit',
                                        'delivered' => 'Acquired',
                                        'completed' => 'Finalized',
                                        'cancelled' => 'Cancelled',
                                        'refunded' => 'Refunded',
                                    ];
                                    $statusColors = [
                                        'pending' => 'bg-secondary/10 text-secondary',
                                        'awaiting_payment' => 'bg-amber-100 text-amber-800',
                                        'paid' => 'bg-primary/10 text-primary',
                                        'processing' => 'bg-indigo-100 text-indigo-800',
                                        'shipped' => 'bg-purple-100 text-purple-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-green-200 text-green-900',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'refunded' => 'bg-orange-100 text-orange-800',
                                    ];
                                @endphp
                                <span class="px-4 py-1.5 rounded-full font-label-caps text-[9px] tracking-[0.2em] uppercase {{ $statusColors[$order->status] ?? 'bg-secondary/10 text-secondary' }}">
                                    {{ $statusLabels[$order->status] ?? strtoupper($order->status) }}
                                </span>
                                <span class="font-label-caps text-[10px] text-secondary tracking-widest opacity-40">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <h2 class="font-headline-md text-3xl text-primary font-bold group-hover:italic transition-all">{{ $order->order_number }}</h2>
                        </div>

                        <div class="flex items-center gap-12 text-right">
                            <div class="hidden md:block">
                                <p class="font-label-caps text-[10px] text-secondary tracking-widest uppercase opacity-40 mb-1">Items</p>
                                <p class="font-body-md text-primary font-bold">{{ $order->items->count() }} Piece(s)</p>
                            </div>
                            <div>
                                <p class="font-label-caps text-[10px] text-secondary tracking-widest uppercase opacity-40 mb-1">Investment</p>
                                <p class="font-headline-md text-2xl text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <span class="material-symbols-outlined text-primary opacity-20 group-hover:opacity-100 group-hover:translate-x-2 transition-all">east</span>
                        </div>
                    </div>

                    @if($order->items->isNotEmpty())
                        <div class="mt-8 pt-8 border-t border-primary/5 flex gap-4 overflow-hidden opacity-40 group-hover:opacity-100 transition-opacity">
                            @foreach($order->items->take(5) as $item)
                                <div class="w-12 h-16 rounded-lg bg-secondary-container overflow-hidden">
                                    <img src="{{ $item->product_image ?? 'https://placehold.co/100x150' }}" class="w-full h-full object-cover" alt="{{ $item->product_name }}">
                                </div>
                            @endforeach
                            @if($order->items->count() > 5)
                                <div class="w-12 h-16 rounded-lg bg-surface-container-highest flex items-center justify-center font-label-caps text-[10px] text-primary">
                                    +{{ $order->items->count() - 5 }}
                                </div>
                            @endif
                        </div>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="mt-16">
            {{ $orders->links('partials.pagination-landing') }}
        </div>
    @endif
</main>
@endsection
