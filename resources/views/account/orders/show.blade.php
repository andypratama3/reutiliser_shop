@extends('layouts.landing')

@section('title', 'Order ' . $order->order_number . ' | RÉUTILISER')

@section('content')
<main class="max-w-[1440px] mx-auto px-8 md:px-16 py-12">
    <!-- Breadcrumb & Actions -->
    <div class="mb-12 flex justify-between items-center reveal-item">
        <a href="{{ route('account.orders.index') }}" class="group flex items-center gap-4 text-secondary hover:text-primary transition-all">
            <span class="material-symbols-outlined text-xl group-hover:-translate-x-2 transition-transform">west</span>
            <span class="font-label-caps text-[11px] tracking-widest uppercase">Back to Archive</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-20 items-start">
        <!-- Left Column: Order Details -->
        <div class="lg:col-span-8 space-y-16 reveal-item">
            <!-- Header Info -->
            <div>
                <div class="flex flex-wrap items-center gap-6 mb-6">
                    <h1 class="font-display-lg text-primary uppercase tracking-tighter">{{ $order->order_number }}</h1>
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
                    <span class="px-6 py-2 rounded-full font-label-caps text-[10px] tracking-[0.2em] uppercase {{ $statusColors[$order->status] ?? 'bg-secondary/10 text-secondary' }}">
                        {{ $statusLabels[$order->status] ?? strtoupper($order->status) }}
                    </span>
                </div>
                <p class="font-body-md text-secondary text-lg opacity-60">
                    Archived on {{ $order->created_at->format('F d, Y \a\t H:i') }}
                </p>
            </div>

            <!-- Items -->
            <div class="space-y-10">
                <h2 class="font-headline-md text-2xl text-primary font-bold border-b border-primary/5 pb-6 uppercase tracking-widest">Archival Pieces</h2>
                @foreach($order->items as $item)
                    <div class="flex flex-col md:flex-row gap-8 group">
                        <div class="w-full md:w-32 h-44 bg-secondary-container rounded-2xl overflow-hidden flex-shrink-0">
                            <img src="{{ $item->product_image ?? 'https://placehold.co/200x300' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="{{ $item->product_name }}">
                        </div>
                        <div class="flex-grow flex flex-col justify-between py-2">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="font-headline-md text-2xl text-primary font-bold">{{ $item->product_name }}</h3>
                                    <span class="font-body-md text-primary font-bold text-lg">Rp {{ number_format($item->total_price, 0, ',', '.') }}</span>
                                </div>
                                <p class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-60 mb-2">
                                    {{ $item->variant_info ?? 'Standard Edition' }}
                                </p>
                                <p class="font-body-md text-secondary text-sm opacity-40">Qty: {{ $item->quantity }} &times; Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Shipping & Logistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-16 border-t border-primary/5">
                <div>
                    <h3 class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40 mb-6">Consignee</h3>
                    <div class="font-body-md text-primary space-y-2">
                        <p class="font-bold text-xl">{{ $order->recipient_name }}</p>
                        <p class="opacity-60">{{ $order->recipient_phone }}</p>
                        <p class="opacity-60 leading-relaxed">{{ $order->shipping_address }}</p>
                        <p class="opacity-60">{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                    </div>
                </div>
                <div>
                    <h3 class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40 mb-6">Logistics</h3>
                    @if($order->shipment)
                        <div class="font-body-md text-primary space-y-4">
                            <div>
                                <p class="font-label-caps text-[9px] opacity-40 uppercase mb-1">Carrier</p>
                                <p class="font-bold">{{ $order->shipment->courier ?? 'Standard Courier' }}</p>
                            </div>
                            <div>
                                <p class="font-label-caps text-[9px] opacity-40 uppercase mb-1">Tracking Number</p>
                                <p class="font-bold tracking-widest text-lg">{{ $order->shipment->tracking_number ?? 'PENDING' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="font-body-md text-secondary italic opacity-40">Logistics information will be updated once your pieces are shipped.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Summary -->
        <aside class="lg:col-span-4 bg-white p-10 md:p-16 rounded-[3rem] shadow-2xl reveal-item lg:sticky lg:top-32 border border-primary/5">
            <h2 class="font-headline-md text-3xl text-primary font-bold mb-12 border-b border-primary/5 pb-6">Summary</h2>
            
            <div class="space-y-6 mb-12">
                <div class="flex justify-between items-center">
                    <span class="font-label-caps text-[12px] text-secondary tracking-widest uppercase opacity-40">Subtotal</span>
                    <span class="font-body-md text-primary font-bold text-lg">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-label-caps text-[12px] text-secondary tracking-widest uppercase opacity-40">Shipping</span>
                    <span class="font-body-md text-primary font-bold text-lg">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between items-center text-primary">
                        <span class="font-label-caps text-[12px] tracking-widest uppercase">Discount</span>
                        <span class="font-body-md font-bold text-lg">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="pt-10 mt-10 border-t-2 border-primary border-dashed opacity-20"></div>
                <div class="flex justify-between items-end">
                    <div>
                        <span class="font-headline-md text-4xl text-primary font-bold">Total</span>
                        <p class="text-[10px] text-secondary tracking-widest uppercase mt-1">Investment • IDR</p>
                    </div>
                    <span class="font-headline-md text-4xl text-primary font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-surface-container-low p-8 rounded-2xl border border-primary/5">
                <h3 class="font-label-caps text-[10px] text-secondary tracking-widest uppercase opacity-40 mb-6">Payment Status</h3>
                <div class="font-body-md text-primary space-y-4">
                    <div class="flex justify-between">
                        <span class="opacity-60 text-sm">Method</span>
                        <span class="font-bold">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="opacity-60 text-sm">Channel</span>
                        <span class="font-bold">{{ $order->payment_channel }}</span>
                    </div>
                    @if($order->payment && $order->payment->va_number)
                        <div class="pt-4 border-t border-primary/5">
                            <span class="opacity-60 text-xs uppercase tracking-widest block mb-1">VA Number</span>
                            <span class="font-bold text-2xl tracking-widest">{{ $order->payment->va_number }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-12 space-y-6">
                @if($order->status === 'awaiting_payment')
                    <p class="text-[11px] text-center text-red-500 uppercase tracking-widest font-bold">Awaiting Payment Confirmation</p>
                @endif
                <div class="flex justify-center gap-10 opacity-30">
                    <div class="flex flex-col items-center gap-2 text-center">
                        <span class="material-symbols-outlined text-lg">verified_user</span>
                        <span class="text-[8px] uppercase tracking-widest">Authenticated</span>
                    </div>
                    <div class="flex flex-col items-center gap-2 text-center">
                        <span class="material-symbols-outlined text-lg">history</span>
                        <span class="text-[8px] uppercase tracking-widest">Archived</span>
                    </div>
                    <div class="flex flex-col items-center gap-2 text-center">
                        <span class="material-symbols-outlined text-lg">eco</span>
                        <span class="text-[8px] uppercase tracking-widest">Circular</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</main>
@endsection
