@extends('layouts.landing')

@section('title', 'Order Confirmed | RÉUTILISER')

@section('content')
<main class="max-w-2xl mx-auto px-8 py-32 text-center">
    <div class="reveal-item">
        <div class="w-24 h-24 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-12 shadow-2xl">
            <span class="material-symbols-outlined text-4xl">check</span>
        </div>
        <p class="font-label-caps text-primary tracking-[0.4em] mb-6 uppercase">Order Confirmed</p>
        <h1 class="font-display-lg text-primary mb-8">Thank you for supporting the movement.</h1>
        <p class="font-body-lg text-secondary leading-relaxed mb-12 opacity-60">
            Your conscious purchase has been recorded. We are now preparing your archival piece for its next chapter.
        </p>
        
        <div class="bg-surface-container-low p-10 rounded-[2rem] border border-primary/5 text-left mb-16 shadow-sm">
            <p class="font-label-caps text-[10px] text-secondary tracking-widest uppercase mb-8 opacity-40">Order Summary</p>
            
            <div class="space-y-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-label-caps text-[10px] text-secondary tracking-widest uppercase opacity-40 mb-1">Order Number</p>
                        <p class="font-headline-md text-2xl text-primary">{{ $order->order_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-label-caps text-[10px] text-secondary tracking-widest uppercase opacity-40 mb-1">Total Amount</p>
                        <p class="font-headline-md text-2xl text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-primary/5">
                    <p class="font-label-caps text-[10px] text-secondary tracking-widest uppercase opacity-40 mb-2">Payment Method</p>
                    <p class="font-body-md text-primary font-bold">
                        @switch($order->payment_method)
                            @case('va_bank')
                                Virtual Account Transfer ({{ $order->payment_channel }})
                                @if($order->payment && $order->payment->va_number)
                                    <br><span class="text-lg tracking-widest mt-2 block">VA: {{ $order->payment->va_number }}</span>
                                @endif
                                @break
                            @case('qris')
                                QRIS Instant Payment
                                @if($order->payment && isset($order->payment->midtrans_response['actions']))
                                    @php
                                        $qrisAction = collect($order->payment->midtrans_response['actions'])->where('name', 'generate-qr-code')->first();
                                    @endphp
                                    @if($qrisAction)
                                        <div class="mt-4 p-4 bg-white rounded-2xl border border-primary/10 inline-block">
                                            <img src="{{ $qrisAction['url'] }}" alt="QRIS" class="w-48 h-48 mx-auto">
                                            <p class="text-[9px] text-center mt-2 opacity-50 uppercase tracking-widest">Scan with your banking app</p>
                                        </div>
                                    @endif
                                @endif
                                @break
                            @case('e_wallet')
                                {{ strtoupper(str_replace('_', ' ', $order->payment_channel)) }}
                                @if($order->payment && isset($order->payment->midtrans_response['actions']))
                                    @php
                                        $deeplink = collect($order->payment->midtrans_response['actions'])->where('name', 'deeplink-redirect')->first();
                                    @endphp
                                    @if($deeplink)
                                        <div class="mt-4">
                                            <a href="{{ $deeplink['url'] }}" class="inline-block bg-[#0081A0] text-white px-8 py-3 rounded-xl font-label-caps text-[10px] tracking-widest hover:opacity-90 transition-all uppercase">Open Payment App</a>
                                        </div>
                                    @endif
                                @endif
                                @break
                            @default
                                {{ strtoupper($order->payment_method) }}
                        @endswitch
                    </p>
                </div>

                @if($order->payment && $order->payment->expires_at)
                <div class="pt-6 border-t border-primary/5">
                    <p class="font-label-caps text-[10px] text-secondary tracking-widest uppercase opacity-40 mb-1">Payment Deadline</p>
                    <p class="font-body-md text-red-500 font-bold italic">{{ $order->payment->expires_at->format('d M Y, H:i') }}</p>
                </div>
                @endif

                <div class="pt-6 border-t border-primary/5">
                    <p class="font-label-caps text-[10px] text-secondary tracking-widest uppercase opacity-40 mb-2">Next Steps</p>
                    <p class="font-body-md text-secondary text-sm leading-relaxed">
                        Detailed payment instructions have been sent via WhatsApp to <span class="text-primary font-bold">{{ $order->recipient_phone }}</span>. 
                        Please complete the transaction within the timeframe to secure your archival pieces.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6 justify-center">
            <a href="{{ route('shop') }}" class="bg-primary text-white px-12 py-6 rounded-full font-label-caps text-[11px] tracking-widest hover:bg-primary-container transition-all shadow-xl uppercase">Continue Browsing</a>
            <a href="{{ route('account.orders.index') }}" class="border border-primary text-primary px-12 py-6 rounded-full font-label-caps text-[11px] tracking-widest hover:bg-primary hover:text-white transition-all uppercase">View My Archive</a>
        </div>
    </div>
</main>
@endsection
