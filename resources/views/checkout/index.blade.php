@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8" x-data="checkoutApp()">
    <h1 class="font-headline text-3xl text-primary mb-8">Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        <div class="lg:col-span-2 space-y-6">
            {{-- Shipping Address --}}
            <div class="bg-surface border border-outline-variant p-6">
                <h2 class="font-semibold text-lg text-on-surface mb-4">Data Pengiriman</h2>

                @if($addresses->isNotEmpty())
                    <div class="mb-4">
                        <label class="font-label-caps text-label-caps text-on-surface-variant mb-2 block">Pilih Alamat Tersimpan</label>
                        <div class="grid gap-2">
                            @foreach($addresses as $addr)
                                <label class="flex items-start gap-3 p-3 border border-outline-variant cursor-pointer hover:bg-surface-variant transition-colors">
                                    <input type="radio" name="saved_address" value="{{ $addr->id }}"
                                           class="mt-1 text-primary focus:ring-primary"
                                           {{ old('saved_address') == $addr->id ? 'checked' : '' }}
                                           @change="fillAddress({{ $addr->toJson() }})">
                                    <div class="font-body-md text-body-md">
                                        <span class="font-semibold text-on-surface">{{ $addr->label }}</span> — {{ $addr->recipient_name }}<br>
                                        <span class="text-on-surface-variant">{{ $addr->address }}, {{ $addr->city }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-center text-on-surface-variant font-body-md text-body-md mb-3">atau isi manual</div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-label-caps text-label-caps text-on-surface mb-1 block">Nama Penerima</label>
                        <input type="text" name="recipient_name" x-model="form.recipient_name"
                               class="w-full border border-outline-variant px-3 py-2 font-body-md text-body-md bg-surface text-on-surface focus:outline-none focus:ring-1 focus:ring-primary" required>
                        @error('recipient_name') <p class="text-error font-body-md text-body-md mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="font-label-caps text-label-caps text-on-surface mb-1 block">No. Telepon</label>
                        <input type="text" name="recipient_phone" x-model="form.recipient_phone"
                               class="w-full border border-outline-variant px-3 py-2 font-body-md text-body-md bg-surface text-on-surface focus:outline-none focus:ring-1 focus:ring-primary" required>
                        @error('recipient_phone') <p class="text-error font-body-md text-body-md mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="font-label-caps text-label-caps text-on-surface mb-1 block">Alamat Lengkap</label>
                        <textarea name="shipping_address" x-model="form.shipping_address"
                                  class="w-full border border-outline-variant px-3 py-2 font-body-md text-body-md bg-surface text-on-surface focus:outline-none focus:ring-1 focus:ring-primary" rows="3" required></textarea>
                        @error('shipping_address') <p class="text-error font-body-md text-body-md mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="font-label-caps text-label-caps text-on-surface mb-1 block">Kota</label>
                        <input type="text" name="shipping_city" x-model="form.shipping_city"
                               class="w-full border border-outline-variant px-3 py-2 font-body-md text-body-md bg-surface text-on-surface focus:outline-none focus:ring-1 focus:ring-primary" required>
                        @error('shipping_city') <p class="text-error font-body-md text-body-md mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="font-label-caps text-label-caps text-on-surface mb-1 block">Provinsi</label>
                        <input type="text" name="shipping_province" x-model="form.shipping_province"
                               class="w-full border border-outline-variant px-3 py-2 font-body-md text-body-md bg-surface text-on-surface focus:outline-none focus:ring-1 focus:ring-primary" required>
                        @error('shipping_province') <p class="text-error font-body-md text-body-md mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="font-label-caps text-label-caps text-on-surface mb-1 block">Kode Pos</label>
                        <input type="text" name="shipping_postal_code" x-model="form.shipping_postal_code"
                               class="w-full border border-outline-variant px-3 py-2 font-body-md text-body-md bg-surface text-on-surface focus:outline-none focus:ring-1 focus:ring-primary" required>
                        @error('shipping_postal_code') <p class="text-error font-body-md text-body-md mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-surface border border-outline-variant p-6">
                <h2 class="font-semibold text-lg text-on-surface mb-4">Metode Pembayaran</h2>

                <div class="space-y-3">
                    {{-- VA Bank --}}
                    <div>
                        <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">Transfer Virtual Account</p>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['BCA', 'BNI', 'Mandiri', 'BRI', 'Permata'] as $bank)
                                <label class="flex items-center gap-2 p-3 border border-outline-variant cursor-pointer hover:bg-surface-variant transition-colors"
                                       :class="{ 'border-primary ring-1 ring-primary': form.payment_channel === '{{ $bank }}' }">
                                    <input type="radio" name="payment_channel" value="{{ $bank }}"
                                           x-model="form.payment_channel"
                                           @change="form.payment_method = 'va_bank'" class="hidden">
                                    <span class="font-body-md text-body-md text-on-surface">{{ $bank }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- QRIS --}}
                    <label class="flex items-center gap-3 p-3 border border-outline-variant cursor-pointer hover:bg-surface-variant transition-colors"
                           :class="{ 'border-primary ring-1 ring-primary': form.payment_method === 'qris' }">
                        <input type="radio" name="payment_channel" value="qris"
                               x-model="form.payment_channel"
                               @change="form.payment_method = 'qris'" class="hidden">
                        <span class="font-body-md text-body-md text-on-surface font-medium">QRIS</span>
                        <span class="font-body-md text-body-md text-on-surface-variant">(GoPay, OVO, Dana, ShopeePay)</span>
                    </label>

                    {{-- E-Wallet --}}
                    <div>
                        <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">Dompet Digital</p>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['Gopay', 'OVO', 'Dana', 'ShopeePay'] as $wallet)
                                <label class="flex items-center gap-2 p-3 border border-outline-variant cursor-pointer hover:bg-surface-variant transition-colors"
                                       :class="{ 'border-primary ring-1 ring-primary': form.payment_channel === '{{ $wallet }}' }">
                                    <input type="radio" name="payment_channel" value="{{ $wallet }}"
                                           x-model="form.payment_channel"
                                           @change="form.payment_method = 'e_wallet'" class="hidden">
                                    <span class="font-body-md text-body-md text-on-surface">{{ $wallet }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <input type="hidden" name="payment_method" x-model="form.payment_method">
            </div>
        </div>

        {{-- Right: Summary --}}
        <div class="space-y-4">
            {{-- Promo Code --}}
            <div class="bg-surface border border-outline-variant p-5">
                <h3 class="font-label-caps text-label-caps text-on-surface mb-3 tracking-wider">KODE PROMO</h3>
                <div class="flex gap-2">
                    <input type="text" x-model="promoCode" placeholder="Masukkan kode"
                           class="flex-1 border border-outline-variant px-3 py-2 font-body-md text-body-md bg-surface text-on-surface uppercase focus:outline-none focus:ring-1 focus:ring-primary">
                    <button type="button" @click="applyPromo()"
                            class="bg-primary text-on-primary px-4 py-2 font-label-caps text-label-caps tracking-wider hover:opacity-90 transition-opacity">
                        Pakai
                    </button>
                </div>
                <p x-text="promoMessage" :class="promoSuccess ? 'text-primary' : 'text-error'"
                   class="font-body-md text-body-md mt-2" x-show="promoMessage"></p>
            </div>

            {{-- Order Summary --}}
            <div class="bg-surface border border-outline-variant p-5 sticky top-20">
                <h3 class="font-label-caps text-label-caps text-on-surface mb-4 tracking-wider">RINGKASAN PESANAN</h3>
                <div class="space-y-2 font-body-md text-body-md">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">
                                {{ $item->product->name }}
                                @if($item->variant) <span class="text-label-caps">({{ $item->variant->size }}/{{ $item->variant->color }})</span> @endif
                                &times; {{ $item->quantity }}
                            </span>
                            <span class="text-on-surface">Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <hr class="my-3 border-outline-variant">
                <div class="space-y-2 font-body-md text-body-md">
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Subtotal</span>
                        <span class="text-on-surface">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Ongkir</span>
                        <span class="text-on-surface">Rp {{ number_format(config('shipping.default_cost', 20000), 0, ',', '.') }}</span>
                    </div>
                    <div x-show="discount > 0" class="flex justify-between text-primary">
                        <span>Diskon</span>
                        <span>- Rp <span x-text="formatCurrency(discount)"></span></span>
                    </div>
                </div>
                <hr class="my-3 border-outline-variant">
                <div class="flex justify-between font-headline text-xl text-on-surface">
                    <span>Total</span>
                    <span>Rp <span x-text="formatCurrency(total)"></span></span>
                </div>

                <button type="submit"
                        class="mt-6 w-full bg-primary text-on-primary py-4 font-label-caps text-label-caps tracking-wider hover:opacity-90 transition-opacity">
                    Pesan Sekarang
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function checkoutApp() {
    return {
        form: {
            // initialize from old() if validation failed, otherwise use sensible defaults
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
        },
        async applyPromo() {
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
            }
        },
    }
}
</script>
@endpush
