@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8" x-data="productApp()">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <div class="space-y-4">
            <div class="bg-surface-container aspect-square overflow-hidden">
                @php
                    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                @endphp
                @if($primaryImage)
                    <img src="{{ Storage::url($primaryImage->path) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover"
                         x-ref="mainImage">
                @else
                    <div class="w-full h-full flex items-center justify-center text-on-surface-variant font-headline text-6xl">
                        &nbsp;
                    </div>
                @endif
            </div>
            @if($product->images->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->images as $image)
                        <button class="aspect-square bg-surface-container overflow-hidden border border-outline-variant hover:border-primary transition-colors {{ $image->is_primary ? 'border-primary' : '' }}"
                                @click="$refs.mainImage.src = '{{ Storage::url($image->path) }}'">
                            <img src="{{ Storage::url($image->path) }}" alt="{{ $image->alt_text ?? $product->name }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div>
                @if($product->category)
                    <p class="font-label-caps text-label-caps text-on-surface-variant tracking-wider mb-2">{{ $product->category->name }}</p>
                @endif
                <h1 class="font-headline text-3xl text-primary">{{ $product->name }}</h1>

                <div class="flex items-center gap-3 mt-4">
                    <span class="font-headline text-2xl text-on-surface">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @if($product->compare_price)
                        <span class="font-body-md text-body-md text-on-surface-variant line-through">Rp {{ number_format($product->compare_price, 0, ',', '.') }}</span>
                        <span class="bg-error text-on-primary font-label-caps text-label-caps px-2 py-0.5">-{{ $product->discount_percent }}%</span>
                    @endif
                </div>

                @if($product->is_limited_edition)
                    <span class="inline-block mt-2 bg-primary text-on-primary font-label-caps text-label-caps px-3 py-1 tracking-wider">LIMITED EDITION</span>
                @endif
            </div>

            @if($product->short_description)
                <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">{{ $product->short_description }}</p>
            @endif

            @if($product->variants->isNotEmpty())
                @php
                    $sizes = $product->variants->pluck('size')->unique()->filter();
                    $colors = $product->variants->pluck('color')->unique()->filter();
                @endphp

                <form method="POST" action="{{ route('cart.add') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @if($sizes->isNotEmpty())
                        <div>
                            <label class="font-label-caps text-label-caps text-on-surface tracking-wider mb-2 block">Ukuran</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($sizes as $size)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="size" value="{{ $size }}"
                                               class="hidden peer"
                                               @change="selectedSize = '{{ $size }}'; updateVariant()">
                                        <span class="block px-4 py-2 border border-outline-variant font-body-md text-body-md text-on-surface peer-checked:bg-primary peer-checked:text-on-primary peer-checked:border-primary transition-colors">
                                            {{ $size }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($colors->isNotEmpty())
                        <div>
                            <label class="font-label-caps text-label-caps text-on-surface tracking-wider mb-2 block">Warna</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach($colors as $color)
                                    @php
                                        $variant = $product->variants->firstWhere('color', $color);
                                        $hex = $variant?->color_hex ?? '#ccc';
                                    @endphp
                                    <label class="cursor-pointer">
                                        <input type="radio" name="color" value="{{ $color }}"
                                               class="hidden peer"
                                               @change="selectedColor = '{{ $color }}'; updateVariant()">
                                        <span class="block w-10 h-10 rounded-full border-2 border-outline-variant peer-checked:border-primary transition-colors"
                                              style="background-color: {{ $hex }}"
                                              title="{{ $color }}"></span>
                                        <span class="block text-center font-label-caps text-label-caps text-on-surface-variant mt-1">{{ $color }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <input type="hidden" name="product_variant_id" x-model="variantId">

                    @if(!$product->isOutOfStock())
                        <div>
                            <label class="font-label-caps text-label-caps text-on-surface tracking-wider mb-2 block">Jumlah</label>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="qty = Math.max(1, qty - 1)"
                                        class="w-10 h-10 border border-outline-variant flex items-center justify-center text-on-surface hover:bg-surface-variant transition-colors">
                                    -
                                </button>
                                <input type="number" name="quantity" x-model.number="qty" min="1" max="10"
                                       class="w-16 text-center border border-outline-variant py-2 font-body-md text-body-md bg-surface text-on-surface focus:outline-none focus:ring-1 focus:ring-primary">
                                <button type="button" @click="qty = Math.min(10, qty + 1)"
                                        class="w-10 h-10 border border-outline-variant flex items-center justify-center text-on-surface hover:bg-surface-variant transition-colors">
                                    +
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full bg-primary text-on-primary py-4 font-label-caps text-label-caps tracking-wider hover:opacity-90 transition-opacity">
                            + Tambah ke Keranjang
                        </button>
                    @endif
                </form>
            @elseif(!$product->isOutOfStock())
                <form method="POST" action="{{ route('cart.add') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">

                    <button type="submit"
                            class="w-full bg-primary text-on-primary py-4 font-label-caps text-label-caps tracking-wider hover:opacity-90 transition-opacity">
                        + Tambah ke Keranjang
                    </button>
                </form>
            @endif

            @if($product->isOutOfStock())
                <div class="bg-surface-container-low p-6 border border-outline-variant">
                    <h3 class="font-label-caps text-label-caps text-on-surface mb-3 tracking-wider">PRODUK HABIS</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-4">Daftar waitlist untuk dapat notifikasi saat produk tersedia kembali.</p>
                    <form method="POST" action="{{ route('products.waitlist', $product) }}" class="space-y-3">
                        @csrf
                        <input type="text" name="email" placeholder="Email kamu" value="{{ auth()->user()?->email }}"
                               class="w-full border border-outline-variant px-4 py-3 font-body-md text-body-md bg-surface text-on-surface focus:outline-none focus:ring-1 focus:ring-primary">
                        <button type="submit"
                                class="w-full bg-primary text-on-primary py-3 font-label-caps text-label-caps tracking-wider hover:opacity-90 transition-opacity">
                            Daftar Waitlist
                        </button>
                    </form>
                </div>
            @endif

            @if($product->description)
                <div class="border-t border-outline-variant pt-6">
                    <h3 class="font-label-caps text-label-caps text-on-surface mb-3 tracking-wider">DESKRIPSI</h3>
                    <div class="font-body-md text-body-md text-on-surface-variant leading-relaxed prose max-w-none">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif

            @if($product->tags->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    @foreach($product->tags as $tag)
                        <span class="bg-surface-variant text-on-surface-variant font-label-caps text-label-caps px-3 py-1">{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if($related->isNotEmpty())
        <section class="mt-20">
            <h2 class="font-headline text-2xl text-primary mb-8">Produk Terkait</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($related as $rel)
                    <a href="{{ route('products.show', $rel->slug) }}"
                       class="group border border-outline-variant bg-surface hover:shadow-lg transition-shadow">
                        <div class="aspect-square overflow-hidden bg-surface-container">
                            @if($rel->primaryImage)
                                <img src="{{ Storage::url($rel->primaryImage->path) }}"
                                     alt="{{ $rel->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant">&nbsp;</div>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="font-body-md text-body-md text-on-surface line-clamp-2">{{ $rel->name }}</p>
                            <p class="font-semibold text-on-surface mt-1">Rp {{ number_format($rel->price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
function productApp() {
    return {
        selectedSize: '',
        selectedColor: '',
        variantId: null,
        qty: 1,
        updateVariant() {
            @if($product->variants->isNotEmpty())
            const variants = @json($product->variants);
            const match = variants.find(v =>
                (!this.selectedSize || v.size === this.selectedSize) &&
                (!this.selectedColor || v.color === this.selectedColor)
            );
            this.variantId = match?.id ?? null;
            @endif
        }
    }
}
</script>
@endpush
