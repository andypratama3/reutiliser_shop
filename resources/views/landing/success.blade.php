@extends('layouts.landing')

@section('title', 'Order Confirmed | RÉUTILISER')

@section('content')
<main class="max-w-2xl mx-auto px-margin-mobile py-32 text-center">
    <div class="reveal-item">
        <div class="w-24 h-24 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-12">
            <span class="material-symbols-outlined text-4xl">check</span>
        </div>
        <p class="font-label-caps text-primary tracking-[0.4em] mb-6">ORDER CONFIRMED</p>
        <h1 class="font-display-lg text-headline-lg text-primary mb-8">Thank you for supporting the movement.</h1>
        <p class="font-body-lg text-secondary leading-relaxed mb-12">
            Your conscious purchase has been recorded. You will receive a confirmation email with artisanal provenance details and shipping tracking shortly.
        </p>
        
        <div class="bg-surface-container-low p-8 border border-outline-variant text-left mb-12">
            <p class="font-label-caps text-[10px] text-secondary mb-4">ORDER SUMMARY</p>
            <div class="flex justify-between items-center">
                <span class="font-body-md text-primary">Order ID: #RE-2024-9841</span>
                <span class="font-headline-md text-primary">$385.00</span>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6 justify-center">
            <a href="{{ url('/shop') }}" class="bg-primary text-on-primary px-12 py-5 font-label-caps text-label-caps hover:bg-primary-container transition-all">CONTINUE BROWSING</a>
            <a href="{{ url('/') }}" class="border border-primary text-primary px-12 py-5 font-label-caps text-label-caps hover:bg-primary hover:text-white transition-all">BACK TO HOME</a>
        </div>
    </div>
</main>
@endsection
