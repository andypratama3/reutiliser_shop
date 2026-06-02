@extends('layouts.landing')

@section('title', 'RÉUTILISER | Conscious Checkout')

@section('content')
<main class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-12">
    <!-- Page Header -->
    <div class="mb-16 reveal-item text-center md:text-left">
        <h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary mb-2">Secure Checkout</h1>
        <p class="font-body-md text-on-surface-variant max-w-lg mx-auto md:mx-0">Finalize your conscious purchase. Every item in your cart is a step toward a more circular future in high fashion.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
        <!-- Left Column: Shipping & Payment -->
        <section class="lg:col-span-7 space-y-16">
            <!-- Contact Information -->
            <div class="reveal-item">
                <div class="flex items-center gap-4 mb-8">
                    <span class="font-label-caps text-label-caps bg-primary text-white px-3 py-1">01</span>
                    <h2 class="font-headline-md text-headline-md text-primary">Contact Information</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-caps text-[10px] text-secondary uppercase tracking-widest">Email Address</label>
                        <input class="form-input-minimal border-b border-outline-variant py-3 bg-transparent focus:border-primary focus:outline-none transition-colors" placeholder="conscious.buyer@reutiliser.com" type="email"/>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-caps text-[10px] text-secondary uppercase tracking-widest">Phone Number</label>
                        <input class="form-input-minimal border-b border-outline-variant py-3 bg-transparent focus:border-primary focus:outline-none transition-colors" placeholder="+44 7700 900000" type="tel"/>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="reveal-item">
                <div class="flex items-center gap-4 mb-8">
                    <span class="font-label-caps text-label-caps bg-primary text-white px-3 py-1">02</span>
                    <h2 class="font-headline-md text-headline-md text-primary">Shipping Details</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="font-label-caps text-[10px] text-secondary uppercase tracking-widest">Full Name</label>
                        <input class="form-input-minimal border-b border-outline-variant py-3 bg-transparent focus:border-primary focus:outline-none transition-colors" placeholder="Alexandra Vauthier" type="text"/>
                    </div>
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="font-label-caps text-[10px] text-secondary uppercase tracking-widest">Address Line 1</label>
                        <input class="form-input-minimal border-b border-outline-variant py-3 bg-transparent focus:border-primary focus:outline-none transition-colors" placeholder="12 Savile Row" type="text"/>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-caps text-[10px] text-secondary uppercase tracking-widest">City</label>
                        <input class="form-input-minimal border-b border-outline-variant py-3 bg-transparent focus:border-primary focus:outline-none transition-colors" placeholder="London" type="text"/>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-caps text-[10px] text-secondary uppercase tracking-widest">Postcode</label>
                        <input class="form-input-minimal border-b border-outline-variant py-3 bg-transparent focus:border-primary focus:outline-none transition-colors" placeholder="W1S 3PQ" type="text"/>
                    </div>
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="font-label-caps text-[10px] text-secondary uppercase tracking-widest">Country</label>
                        <select class="form-input-minimal border-b border-outline-variant py-3 bg-transparent focus:border-primary focus:outline-none appearance-none cursor-pointer transition-colors">
                            <option>United Kingdom</option>
                            <option>France</option>
                            <option>Germany</option>
                            <option>Italy</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Shipping Method Selection -->
            <div class="reveal-item">
                <div class="flex items-center gap-4 mb-8">
                    <span class="font-label-caps text-label-caps bg-primary text-white px-3 py-1">03</span>
                    <h2 class="font-headline-md text-headline-md text-primary">Shipping Method</h2>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    @foreach($shippingMethods as $method)
                    <label class="flex items-center justify-between p-6 bg-surface-container-low border border-outline-variant cursor-pointer hover:border-primary transition-all group shipping-option" data-price="{{ $method['price'] }}">
                        <div class="flex items-center gap-6">
                            <input {{ $loop->first ? 'checked' : '' }} class="w-4 h-4 text-primary border-outline focus:ring-primary" name="shipping_method" type="radio" value="{{ $method['id'] }}"/>
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-primary">{{ $method['icon'] }}</span>
                                <div>
                                    <div class="font-label-caps text-label-caps text-primary">{{ $method['name'] }}</div>
                                    <div class="text-[10px] text-secondary uppercase tracking-tighter">{{ $method['time'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="font-body-md text-primary">{{ $method['price'] > 0 ? '£' . number_format($method['price'], 2) : 'FREE' }}</div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Payment Method -->
            <div class="reveal-item">
                <div class="flex items-center gap-4 mb-8">
                    <span class="font-label-caps text-label-caps bg-primary text-white px-3 py-1">04</span>
                    <h2 class="font-headline-md text-headline-md text-primary">Payment Method</h2>
                </div>
                <div class="space-y-4">
                    <!-- Digital Wallet Option -->
                    <label class="flex items-center p-6 bg-surface-container-low border border-outline-variant cursor-pointer hover:border-primary transition-all group payment-option">
                        <input checked="" class="w-4 h-4 text-primary border-outline focus:ring-primary" name="payment" type="radio"/>
                        <div class="ml-6 flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
                            <div>
                                <div class="font-label-caps text-label-caps text-primary">Digital Wallet</div>
                                <div class="text-sm text-secondary">Apple Pay, Google Pay, or PayPal</div>
                            </div>
                        </div>
                    </label>
                    <!-- Bank Transfer Option -->
                    <label class="flex items-center p-6 bg-surface-container-low border border-outline-variant cursor-pointer hover:border-primary transition-all group payment-option">
                        <input class="w-4 h-4 text-primary border-outline focus:ring-primary" name="payment" type="radio"/>
                        <div class="ml-6 flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary">account_balance</span>
                            <div>
                                <div class="font-label-caps text-label-caps text-primary">Bank Transfer</div>
                                <div class="text-sm text-secondary">Direct wire for exclusive archival pieces</div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </section>

        <!-- Right Column: Order Summary -->
        <aside class="lg:col-span-5 bg-secondary-container p-8 md:p-12 sticky top-24 reveal-item">
            <h2 class="font-headline-md text-headline-md text-primary mb-8 border-b border-outline-variant pb-4">Order Summary</h2>
            
            <!-- Cart Items -->
            <div class="space-y-8 mb-12">
                @foreach($cartItems as $item)
                <div class="flex gap-6">
                    <div class="w-24 h-32 bg-surface overflow-hidden flex-shrink-0">
                        <img class="w-full h-full object-cover" src="{{ $item['image'] }}" alt="{{ $item['name'] }}"/>
                    </div>
                    <div class="flex-grow flex flex-col justify-between py-1">
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="font-body-md text-primary font-medium leading-tight">{{ $item['name'] }}</h3>
                                <span class="font-body-md text-primary font-bold">£{{ number_format($item['price'], 0) }}</span>
                            </div>
                            <p class="font-label-caps text-[10px] text-secondary mt-1">SIZE: {{ $item['size'] }} / {{ $item['note'] }}</p>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="font-label-caps text-[10px] text-primary border border-primary px-2 py-1">QTY: {{ $item['qty'] }}</div>
                            <button class="text-[10px] uppercase underline tracking-tighter text-secondary hover:text-primary">Remove</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Promo Code -->
            <div class="mb-12">
                <div class="flex gap-4">
                    <input class="flex-grow bg-transparent border-b border-outline-variant py-2 font-label-caps text-[10px] focus:border-primary focus:outline-none transition-colors" placeholder="ENTER PROMO CODE" type="text"/>
                    <button class="font-label-caps text-[10px] text-primary hover:tracking-widest transition-all">APPLY</button>
                </div>
            </div>

            <!-- Sustainability Impact Note -->
            <div class="bg-primary text-on-primary p-6 mb-12 flex items-start gap-4">
                <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">eco</span>
                <div>
                    <p class="font-label-caps text-[10px] tracking-widest text-white mb-1 uppercase">Environmental Impact</p>
                    <p class="font-body-md text-[12px] leading-relaxed italic opacity-90">
                        "Your choice today diverted 2.4kg of waste and saved 800L of water. Thank you for voting for a circular future."
                    </p>
                </div>
            </div>

            <!-- Totals -->
            <div class="space-y-4 font-body-md">
                <div class="flex justify-between items-center">
                    <span class="text-secondary uppercase text-[10px] tracking-widest">Subtotal</span>
                    <span class="text-primary">£{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-secondary uppercase text-[10px] tracking-widest">Shipping</span>
                    <span class="text-primary" id="summary-shipping">FREE</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-secondary uppercase text-[10px] tracking-widest">Carbon Tax Offset</span>
                    <span class="text-primary italic">Included</span>
                </div>
                <div class="pt-6 mt-6 border-t border-dashed border-outline-variant">
                    <div class="flex justify-between items-end">
                        <span class="font-headline-md text-headline-md text-primary">Total</span>
                        <div class="text-right">
                            <span class="font-headline-md text-headline-md text-primary" id="summary-total">£{{ number_format($total, 2) }}</span>
                            <p class="text-[10px] text-secondary uppercase tracking-tighter mt-1">VAT Included • GBP</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Action -->
            <button class="w-full mt-12 bg-primary text-on-primary font-label-caps text-label-caps py-6 tracking-widest uppercase transition-all duration-500 hover:bg-primary-container transform active:scale-[0.98]">
                Complete Purchase
            </button>
            <div class="mt-8 flex justify-center gap-6 opacity-40 grayscale">
                <span class="material-symbols-outlined">verified_user</span>
                <span class="material-symbols-outlined">lock</span>
                <span class="material-symbols-outlined">credit_card</span>
            </div>
            <p class="mt-6 text-[9px] text-center text-secondary uppercase tracking-widest leading-relaxed">
                SECURE ENCRYPTED TRANSACTION • 14-DAY RETURNS POLICY • AUTHENTICITY GUARANTEED
            </p>
        </aside>
    </div>
</main>
@endsection

@push('js')
<script>
    // Dynamic Shipping Update Simulation
    const subtotal = {{ $subtotal }};
    const shippingOptions = document.querySelectorAll('input[name="shipping_method"]');
    const shippingDisplay = document.getElementById('summary-shipping');
    const totalDisplay = document.getElementById('summary-total');

    shippingOptions.forEach(option => {
        option.addEventListener('change', () => {
            const price = parseFloat(option.closest('.shipping-option').dataset.price);
            const total = subtotal + price;
            
            shippingDisplay.textContent = price > 0 ? `£${price.toFixed(2)}` : 'FREE';
            totalDisplay.textContent = `£${total.toFixed(2)}`;
            
            // Visual feedback on the selection
            document.querySelectorAll('.shipping-option').forEach(el => {
                el.classList.remove('border-primary', 'bg-white');
            });
            option.closest('.shipping-option').classList.add('border-primary', 'bg-white');
        });
    });

    // Payment Selection Feedback
    const paymentOptions = document.querySelectorAll('input[name="payment"]');
    paymentOptions.forEach(option => {
        option.addEventListener('change', () => {
            document.querySelectorAll('.payment-option').forEach(el => {
                el.classList.remove('border-primary', 'bg-white');
            });
            option.closest('.payment-option').classList.add('border-primary', 'bg-white');
        });
    });
</script>
@endpush
