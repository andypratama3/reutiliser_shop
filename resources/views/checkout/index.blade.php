@extends('layouts.landing')

@section('title', 'Secure Checkout | RÉUTILISER')

@section('content')
<main class="max-w-[1440px] mx-auto px-8 md:px-16 py-12" x-data="checkoutApp()">
    <!-- Page Header -->
    <div class="mb-20 reveal-item text-center md:text-left">
        <h1 class="font-display-lg text-primary mb-4 uppercase tracking-tighter">Secure Checkout</h1>
        <p class="font-body-md text-secondary max-w-lg mx-auto md:mx-0 text-lg opacity-60">Finalize your conscious purchase. Every item in your collection is a step toward a more circular future.</p>
    </div>

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf

        {{-- Display stock or general validation errors --}}
        @if($errors->any())
            <div class="mb-10 p-6 rounded-2xl bg-red-50 border border-red-100 text-red-800 font-body-md">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-20 items-start">
            <!-- Left Column: Shipping & Payment -->
            <section class="lg:col-span-7 space-y-20">
                
                {{-- 1. Shipping Address --}}
                <div class="reveal-item">
                    <div class="flex items-center gap-6 mb-12">
                        <span class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-label-caps text-lg">1</span>
                        <h2 class="font-headline-md text-3xl text-primary font-bold">Shipping Details</h2>
                    </div>

                    @if($addresses->isNotEmpty())
                        <div class="mb-10">
                            <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40 mb-4 block">Saved Addresses</label>
                            <div class="grid gap-4">
                                @foreach($addresses as $addr)
                                    <label class="flex items-start gap-4 p-6 bg-surface-container-low rounded-2xl cursor-pointer hover:bg-primary/5 transition-all border border-transparent"
                                           :class="selectedAddress == {{ $addr->id }} ? 'border-primary/20 bg-primary/5' : ''">
                                        <input type="radio" name="saved_address" value="{{ $addr->id }}"
                                               class="mt-1 text-primary focus:ring-primary border-outline-variant"
                                               {{ old('saved_address') == $addr->id ? 'checked' : '' }}
                                               @change="fillAddress({{ $addr->toJson() }}); selectedAddress = {{ $addr->id }}">
                                        <div class="font-body-md">
                                            <span class="font-bold text-primary">{{ $addr->label }}</span> — {{ $addr->recipient_name }}<br>
                                            <span class="text-secondary text-sm">{{ $addr->address }}, {{ $addr->city }}, {{ $addr->province }}</span>
                                        </div>
                                    </label>
                                @endforeach
                                <label class="flex items-start gap-4 p-6 bg-surface-container-low rounded-2xl cursor-pointer hover:bg-primary/5 transition-all border border-transparent"
                                       :class="selectedAddress == 0 ? 'border-primary/20 bg-primary/5' : ''">
                                    <input type="radio" name="saved_address" value="0"
                                           class="mt-1 text-primary focus:ring-primary border-outline-variant"
                                           @change="selectedAddress = 0" :checked="selectedAddress == 0">
                                    <div class="font-body-md">
                                        <span class="font-bold text-primary text-sm uppercase tracking-widest">New Address / Manual Entry</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10" x-show="selectedAddress == 0" x-transition>
                        <div class="space-y-3 md:col-span-2">
                            <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">Recipient Name</label>
                            <input type="text" name="recipient_name" x-model="form.recipient_name"
                                   class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" 
                                   placeholder="Alexandra Vauthier" required>
                        </div>
                        <div class="space-y-3">
                            <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">Phone Number</label>
                            <input type="text" name="recipient_phone" x-model="form.recipient_phone"
                                   class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" 
                                   placeholder="+62 812 3456 7890" required>
                        </div>
                        <div class="space-y-3 md:col-span-2">
                            <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">Complete Address</label>
                            <textarea name="shipping_address" x-model="form.shipping_address"
                                      class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" 
                                      rows="3" placeholder="12 Savile Row" required></textarea>
                        </div>
                        <div class="space-y-3">
                            <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">City</label>
                            <input type="text" name="shipping_city" x-model="form.shipping_city"
                                   class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" 
                                   placeholder="London" required>
                        </div>
                        <div class="space-y-3">
                            <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">Province</label>
                            <input type="text" name="shipping_province" x-model="form.shipping_province"
                                   class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" 
                                   placeholder="Greater London" required>
                        </div>
                        <div class="space-y-3">
                            <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40">Postcode</label>
                            <input type="text" name="shipping_postal_code" x-model="form.shipping_postal_code"
                                   class="w-full bg-surface-container-low p-4 rounded-xl focus:ring-2 focus:ring-primary/5 focus:outline-none transition-all text-primary font-body-md" 
                                   placeholder="W1S 3PQ" required>
                        </div>
                    </div>

                    {{-- Summary of selected address when collapsed --}}
                    <div x-show="selectedAddress != 0" class="p-6 bg-primary/5 rounded-2xl border border-primary/10">
                        <div class="flex justify-between items-center">
                            <div class="font-body-md">
                                <p class="font-bold text-primary" x-text="form.recipient_name"></p>
                                <p class="text-secondary text-sm" x-text="form.shipping_address"></p>
                                <p class="text-secondary text-sm" x-text="form.shipping_city + ', ' + form.shipping_province + ' ' + form.shipping_postal_code"></p>
                                <p class="text-secondary text-sm" x-text="form.recipient_phone"></p>
                            </div>
                            <button type="button" @click="selectedAddress = 0" class="text-primary font-label-caps text-[10px] tracking-widest uppercase hover:underline">Change</button>
                        </div>
                    </div>
                </div>

                {{-- 2. Payment Method --}}
                <div class="reveal-item">
                    <div class="flex items-center gap-6 mb-12">
                        <span class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-label-caps text-lg">2</span>
                        <h2 class="font-headline-md text-3xl text-primary font-bold">Payment Method</h2>
                    </div>

                    <div class="space-y-10">
                        {{-- VA Bank --}}
                        <div>
                            <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40 mb-6 block">Virtual Account Transfer</label>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                @foreach(['BCA', 'BNI', 'Mandiri', 'BRI', 'Permata'] as $bank)
                                    <label class="flex flex-col items-center justify-center p-6 bg-surface-container-low rounded-2xl cursor-pointer hover:bg-primary/5 transition-all border border-transparent"
                                           :class="form.payment_channel === '{{ $bank }}' ? 'border-primary/20 bg-primary/5' : ''">
                                        <input type="radio" name="payment_channel" value="{{ $bank }}"
                                               x-model="form.payment_channel"
                                               @change="form.payment_method = 'va_bank'" class="hidden">
                                        <span class="font-body-md text-primary font-bold">{{ $bank }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- QRIS --}}
                        <div>
                            <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40 mb-6 block">Instant Payment</label>
                            <label class="flex items-center justify-between p-6 bg-surface-container-low rounded-2xl cursor-pointer hover:bg-primary/5 transition-all border border-transparent"
                                   :class="form.payment_method === 'qris' ? 'border-primary/20 bg-primary/5' : ''">
                                <div class="flex items-center gap-6">
                                    <input type="radio" name="payment_channel" value="qris"
                                           x-model="form.payment_channel"
                                           @change="form.payment_method = 'qris'" class="hidden">
                                    <span class="material-symbols-outlined text-primary text-3xl opacity-60">qr_code_2</span>
                                    <div>
                                        <p class="font-body-md text-primary font-bold text-lg">QRIS</p>
                                        <p class="font-label-caps text-[10px] text-secondary tracking-widest opacity-60">GoPay, OVO, Dana, ShopeePay</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        {{-- E-Wallet --}}
                        <div>
                            <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40 mb-6 block">Digital Wallets</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach(['Gopay', 'OVO', 'Dana', 'ShopeePay'] as $wallet)
                                    <label class="flex flex-col items-center justify-center p-6 bg-surface-container-low rounded-2xl cursor-pointer hover:bg-primary/5 transition-all border border-transparent"
                                           :class="form.payment_channel === '{{ $wallet }}' ? 'border-primary/20 bg-primary/5' : ''">
                                        <input type="radio" name="payment_channel" value="{{ $wallet }}"
                                               x-model="form.payment_channel"
                                               @change="form.payment_method = 'e_wallet'" class="hidden">
                                        <span class="font-body-md text-primary font-bold">{{ $wallet }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="payment_method" x-model="form.payment_method">
                </div>
            </section>

            <!-- Right Column: Order Review -->
            <aside class="lg:col-span-5 bg-white p-10 md:p-16 rounded-[3rem] shadow-2xl reveal-item lg:sticky lg:top-32">
                <h2 class="font-headline-md text-3xl text-primary font-bold mb-12 border-b border-primary/5 pb-6">Order Review</h2>
                
                <!-- Items List -->
                <div class="space-y-10 mb-16 max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                    @foreach($cart->items as $item)
                    <div class="flex gap-8 group">
                        <div class="w-20 h-28 bg-secondary-container rounded-2xl overflow-hidden flex-shrink-0">
                            <img src="{{ $item->product?->primaryImage?->path ?? 'https://placehold.co/200x200' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="{{ $item->product?->name ?? 'Product' }}">
                        </div>
                        <div class="flex-grow flex flex-col justify-between py-1">
                            <div>
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="font-body-md text-primary font-bold text-lg leading-tight">{{ $item->product?->name ?? 'Unknown Product' }}</h3>
                                    <span class="font-body-md text-primary font-bold">Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                                </div>
                                <p class="font-label-caps text-[10px] text-secondary tracking-widest uppercase">
                                    @if($item->variant)
                                        {{ $item->variant->size }} / {{ $item->variant->color }}
                                    @else
                                        Standard Size
                                    @endif
                                </p>
                            </div>
                            <p class="font-label-caps text-secondary opacity-40 text-[10px]">Qty: {{ $item->quantity }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Promo Code -->
                <div class="mb-12">
                    <label class="font-label-caps text-[11px] text-secondary tracking-widest uppercase opacity-40 mb-4 block">Promotional Code</label>
                    <div class="flex bg-surface-container-low rounded-2xl p-2 shadow-sm">
                        <input type="text" x-model="promoCode" class="bg-transparent border-none w-full focus:ring-0 text-sm text-primary placeholder:text-secondary/40 px-4 uppercase font-body-md" placeholder="ARCHIVE2024"/>
                        <button type="button" @click="applyPromo()" class="bg-primary text-white px-6 h-12 flex items-center justify-center rounded-xl font-label-caps text-[10px] tracking-widest hover:bg-primary-container transition-all">APPLY</button>
                    </div>
                    <p x-text="promoMessage" :class="promoSuccess ? 'text-primary' : 'text-red-500'"
                       class="font-body-md text-[12px] mt-4" x-show="promoMessage"></p>
                </div>

                <!-- Totals -->
                <div class="space-y-6 pt-10 border-t border-primary/5">
                    <div class="flex justify-between items-center">
                        <span class="font-label-caps text-[12px] text-secondary tracking-widest uppercase opacity-40">Subtotal</span>
                        <span class="font-body-md text-primary font-bold text-lg">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-label-caps text-[12px] text-secondary tracking-widest uppercase opacity-40">Shipping</span>
                        <span class="font-body-md text-primary font-bold text-lg" x-text="'Rp ' + formatCurrency(shippingCost)"></span>
                    </div>
                    <div x-show="discount > 0" class="flex justify-between items-center text-primary">
                        <span class="font-label-caps text-[12px] tracking-widest uppercase">Discount</span>
                        <span class="font-body-md font-bold text-lg">- Rp <span x-text="formatCurrency(discount)"></span></span>
                    </div>
                    <div class="pt-10 mt-10 border-t-2 border-primary border-dashed opacity-20"></div>
                    <div class="flex justify-between items-end">
                        <div>
                            <span class="font-headline-md text-4xl text-primary font-bold">Total</span>
                            <p class="text-[10px] text-secondary tracking-widest uppercase mt-1">VAT Included • IDR</p>
                        </div>
                        <span class="font-headline-md text-4xl text-primary font-bold">Rp <span x-text="formatCurrency(total)"></span></span>
                    </div>
                </div>

                <!-- Action -->
                <div class="mt-16 space-y-8">
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
                </div>
            </aside>
        </div>
    </form>
</main>
@endsection

@push('js')
<script>
function checkoutApp() {
    return {
        selectedAddress: {{ $addresses->isNotEmpty() ? $addresses->first()->id : 0 }},
        form: {
            recipient_name: @json(old('recipient_name', auth()->user()->name)),
            recipient_phone: @json(old('recipient_phone', auth()->user()->phone ?? '')),
            shipping_address: @json(old('shipping_address', '')),
            shipping_city: @json(old('shipping_city', '')),
            shipping_province: @json(old('shipping_province', '')),
            shipping_postal_code: @json(old('shipping_postal_code', '')),
            payment_method: @json(old('payment_method', '')),
            payment_channel: @json(old('payment_channel', '')),
        },
        promoCode: '',
        promoMessage: '',
        promoSuccess: false,
        discount: 0,
        subtotal: {{ $cart->subtotal }},
        shippingCost: {{ config('shipping.default_cost', 20000) }},
        get total() {
            return Math.max(0, this.subtotal - this.discount + this.shippingCost);
        },
        formatCurrency(val) {
            return new Intl.NumberFormat('id-ID').format(val);
        },
        fillAddress(addr) {
            this.form.recipient_name    = addr.recipient_name;
            this.form.recipient_phone   = addr.phone;
            this.form.shipping_address  = addr.address;
            this.form.shipping_city     = addr.city;
            this.form.shipping_province = addr.province;
            this.form.shipping_postal_code = addr.postal_code;
            // Update shipping cost if city matches a zone
            this.updateShippingCost(addr.city);
        },
        updateShippingCost(city) {
            const zones = @json(config('shipping.zones', []));
            const cityLower = city.toLowerCase().trim();
            let found = false;
            for (const [key, cost] of Object.entries(zones)) {
                if (cityLower.includes(key)) {
                    this.shippingCost = cost;
                    found = true;
                    break;
                }
            }
            if (!found) {
                this.shippingCost = {{ config('shipping.default_cost', 20000) }};
            }
        },
        async applyPromo() {
            if (!this.promoCode) return;
            
            const res = await fetch('{{ route('checkout.promo') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({ code: this.promoCode }),
            });
            const data = await res.json();
            if (res.ok) {
                this.promoMessage = data.message;
                this.promoSuccess = true;
                if (data.discount_type === 'percentage') {
                    this.discount = this.subtotal * (data.discount_value / 100);
                } else if (data.discount_type === 'fixed_amount') {
                    this.discount = data.discount_value;
                }
            } else {
                this.promoMessage = data.error;
                this.promoSuccess = false;
                this.discount = 0;
            }
        },
    }
}
</script>
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #2a4a38;
        border-radius: 10px;
    }
</style>
@endpush
