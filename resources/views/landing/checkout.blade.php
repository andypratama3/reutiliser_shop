@extends('layouts.landing')

@section('title', 'Secure Checkout | RÉUTILISER')

@section('content')
<main class="max-w-[1440px] mx-auto px-8 md:px-16 py-12">
    <!-- Page Header -->
    <div class="mb-20 reveal-item text-center md:text-left">
        <h1 class="font-display-lg text-primary mb-4 uppercase tracking-tighter">Secure Checkout</h1>
        <p class="font-body-md text-secondary max-w-lg mx-auto md:mx-0 text-lg opacity-60">Finalize your conscious purchase. Every item in your collection is a step toward a more circular future.</p>
    </div>

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf

        {{-- Display stock validation error from CheckoutController --}}
        @if($errors->has('stock'))
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-100 text-red-800">
                {{ $errors->first('stock') }}
            </div>
        @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-20 items-start">
        <!-- Left Column: Shipping & Payment -->
        <section class="lg:col-span-7 space-y-20">
            <!-- Contact Information -->
            <div class="reveal-item">
                <div class="flex items-center gap-6 mb-12">
                    <span class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-label-caps text-lg">1</span>
                    <h2 class="font-headline-md text-3xl text-primary font-bold">Contact Information</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">Email Address</label>
                        <input name="email" value="{{ old('email') }}" class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" placeholder="conscious.buyer@reutiliser.com" type="email"/>
                    </div>
                    <div class="space-y-3">
                        <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">Phone Number</label>
                        <input name="recipient_phone" value="{{ old('recipient_phone') }}" class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" placeholder="+44 7700 900000" type="tel"/>
                    </div>
                </div>
            </div>

            <!-- Shipping Details -->
            <div class="reveal-item">
                <div class="flex items-center gap-6 mb-12">
                    <span class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-label-caps text-lg">2</span>
                    <h2 class="font-headline-md text-3xl text-primary font-bold">Shipping Details</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3 md:col-span-2">
                        <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">Full Name</label>
                        <input name="recipient_name" value="{{ old('recipient_name') }}" class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" placeholder="Alexandra Vauthier" type="text"/>
                    </div>
                    <div class="space-y-3 md:col-span-2">
                        <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">Address Line 1</label>
                        <input name="shipping_address" value="{{ old('shipping_address') }}" class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" placeholder="12 Savile Row" type="text"/>
                    </div>
                    <div class="space-y-3">
                        <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">City</label>
                        <input name="shipping_city" value="{{ old('shipping_city') }}" class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" placeholder="London" type="text"/>
                    </div>
                    <div class="space-y-3">
                        <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">Postcode</label>
                        <input name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" placeholder="W1S 3PQ" type="text"/>
                    </div>
                </div>
            </div>

            <!-- Shipping Method -->
            <div class="reveal-item">
                <div class="flex items-center gap-6 mb-12">
                    <span class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-label-caps text-lg">3</span>
                    <h2 class="font-headline-md text-3xl text-primary font-bold">Shipping Method</h2>
                </div>
                <div class="space-y-4">
                    @foreach($shippingMethods as $method)
                    <label class="flex items-center justify-between p-6 bg-surface-container-low rounded-2xl cursor-pointer hover:bg-primary/5 transition-all group border border-transparent hover:border-primary/10">
                        <div class="flex items-center gap-6">
                            <input {{ $loop->first ? 'checked' : '' }} name="shipping" type="radio" class="w-4 h-4 text-primary border-0 focus:ring-0 cursor-pointer"/>
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-primary text-2xl opacity-60">{{ $method['icon'] }}</span>
                                <div>
                                    <p class="font-body-md text-primary font-bold">{{ $method['name'] }}</p>
                                    <p class="font-label-caps text-[10px] text-secondary tracking-widest opacity-60">{{ $method['time'] }}</p>
                                </div>
                            </div>
                        </div>
                        <span class="font-body-md text-primary font-bold">{{ $method['price'] > 0 ? 'Rp ' . number_format($method['price'], 0, ',', '.') : 'FREE' }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Payment Method -->
            <div class="reveal-item">
                <div class="flex items-center gap-6 mb-12">
                    <span class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-label-caps text-lg">4</span>
                    <h2 class="font-headline-md text-3xl text-primary font-bold">Payment Method</h2>
                </div>
                <div class="space-y-4">
                    <label class="flex items-center p-6 bg-surface-container-low rounded-2xl cursor-pointer hover:bg-primary/5 transition-all group">
                        <input checked name="payment" type="radio" class="w-4 h-4 text-primary border-0 focus:ring-0 cursor-pointer"/>
                        <div class="ml-6 flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary text-2xl opacity-60">account_balance_wallet</span>
                            <div>
                                <p class="font-body-md text-primary font-bold">Digital Wallet</p>
                                <p class="text-[11px] text-secondary opacity-60">Apple Pay, Google Pay, or PayPal</p>
                            </div>
                        </div>
                    </label>
                    <label class="flex items-center p-6 bg-surface-container-low rounded-2xl cursor-pointer hover:bg-primary/5 transition-all group">
                        <input name="payment" type="radio" class="w-4 h-4 text-primary border-0 focus:ring-0 cursor-pointer"/>
                        <div class="ml-6 flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary text-2xl opacity-60">credit_card</span>
                            <div>
                                <p class="font-body-md text-primary font-bold">Credit / Debit Card</p>
                                <p class="text-[11px] text-secondary opacity-60">All major cards accepted through secure encryption</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </section>

        <!-- Right Column: Order Review -->
        <aside class="lg:col-span-5 bg-white p-10 md:p-16 rounded-[3rem] shadow-2xl reveal-item lg:sticky lg:top-32">
            <h2 class="font-headline-md text-3xl text-primary font-bold mb-12 border-b border-primary/5 pb-6">Order Review</h2>
            
            <!-- Items List -->
            <div class="space-y-10 mb-16">
                @foreach($cartItems as $item)
                <div class="flex gap-8 group">
                    <div class="w-24 h-32 bg-secondary-container rounded-2xl overflow-hidden flex-shrink-0">
                        <img src="{{ $item['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="{{ $item['name'] }}">
                    </div>
                    <div class="flex-grow flex flex-col justify-between py-2">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-body-md text-primary font-bold text-xl leading-tight">{{ $item['name'] }}</h3>
                                    <span class="font-body-md text-primary font-bold text-lg">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                            </div>
                            <p class="font-label-caps text-[10px] text-secondary tracking-widest">{{ $item['size'] }} / {{ $item['note'] }}</p>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <p class="font-label-caps text-secondary opacity-40">Qty: {{ $item['qty'] }}</p>
                            <button class="text-[10px] uppercase underline text-secondary hover:text-primary tracking-widest font-bold border-0 p-0 hover:bg-transparent">Modify</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Totals -->
            <div class="space-y-6 pt-10 border-t border-primary/5">
                <div class="flex justify-between items-center">
                    <span class="font-label-caps text-[12px] text-secondary tracking-widest uppercase opacity-40">Subtotal</span>
                    <span class="font-body-md text-primary font-bold text-lg">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-label-caps text-[12px] text-secondary tracking-widest uppercase opacity-40">Shipping</span>
                    <span class="font-body-md text-primary font-bold text-lg italic">FREE</span>
                </div>
                <div class="pt-10 mt-10 border-t-2 border-primary border-dashed opacity-20"></div>
                <div class="flex justify-between items-end">
                    <div>
                        <span class="font-headline-md text-4xl text-primary font-bold">Total</span>
                        <p class="text-[10px] text-secondary tracking-widest uppercase mt-1">PPN Termasuk • IDR</p>
                    </div>
                    <span class="font-headline-md text-4xl text-primary font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Action -->
            <div class="mt-16 space-y-8">
                <input type="hidden" name="shipping_province" value="DKI Jakarta">
                <input type="hidden" name="payment_method" value="va_bank">
                <input type="hidden" name="payment_channel" value="BCA">
                <button type="submit" class="block w-full text-center bg-primary text-white py-8 rounded-full font-label-caps tracking-[0.3em] text-sm font-bold hover:bg-primary-container transition-all shadow-2xl">CONFIRM PURCHASE</button>
                
                <div class="flex justify-center gap-10 opacity-30">
                    <div class="flex flex-col items-center gap-2 text-center">
                        <span class="material-symbols-outlined text-lg">lock</span>
                        <span class="text-[8px] uppercase tracking-widest">Encrypted</span>
                    </div>
                    <div class="flex flex-col items-center gap-2 text-center">
                        <span class="material-symbols-outlined text-lg">verified_user</span>
                        <span class="text-[8px] uppercase tracking-widest">Authenticated</span>
                    </div>
                    <div class="flex flex-col items-center gap-2 text-center">
                        <span class="material-symbols-outlined text-lg">local_shipping</span>
                        <span class="text-[8px] uppercase tracking-widest">Tracked</span>
                    </div>
                </div>
                
                <p class="text-[9px] text-center text-secondary uppercase tracking-[0.2em] leading-loose opacity-40">
                    BY CONFIRMING, YOU AGREE TO OUR ARCHIVAL SALES POLICY • 14-DAY RETURNS ON RECONSTRUCTED PIECES
                </p>
            </div>
        </aside>
    </div>
    </form>
</main>
@endsection
