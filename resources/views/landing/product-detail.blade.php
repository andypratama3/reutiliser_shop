@extends('layouts.landing')

@section('title', $product['name'] . ' | RÉUTILISER')

@section('content')
<main class="max-w-container-max mx-auto px-4 md:px-12 py-12" x-data="productDetail()">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        <!-- Product Images -->
        <div class="lg:col-span-7 space-y-8 reveal-item">
            <div class="aspect-[3/4] bg-secondary-container rounded-[2rem] overflow-hidden border border-primary p-2">
                <img src="{{ $product['image'] }}" class="w-full h-full object-cover rounded-[1.5rem]" alt="{{ $product['name'] }}" x-ref="mainImage">
            </div>
            @if($productModel->images->count() > 1)
            <div class="grid grid-cols-2 gap-8">
                @foreach($productModel->images as $img)
                <div class="aspect-square bg-surface-container-low rounded-[1.5rem] overflow-hidden border border-primary/20 p-2 cursor-pointer" @click="$refs.mainImage.src = '{{ $img->url }}'">
                    <img src="{{ $img->url }}" class="w-full h-full object-cover rounded-[1rem] hover:grayscale-0 transition-all duration-500 {{ $img->is_primary ? '' : 'grayscale opacity-60 hover:opacity-100' }}" alt="Detail">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <aside class="lg:col-span-5 space-y-12 reveal-item border border-primary p-12 rounded-[2.5rem] bg-surface h-fit">
            <div>
                <p class="font-label-caps text-secondary mb-4 tracking-widest text-[12px]">{{ $product['category'] }} / {{ $product['material'] }}</p>
                <h1 class="font-headline-lg text-primary leading-tight mb-4">{{ $product['name'] }}</h1>
                <div class="flex items-center gap-4">
                    <p class="font-headline-md text-primary text-3xl font-bold" x-text="'Rp ' + Number(selectedPrice).toLocaleString('id-ID')">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                    @if($product['compare_price'])
                    <p class="font-headline-md text-secondary text-xl line-through">Rp {{ number_format($product['compare_price'], 0, ',', '.') }}</p>
                    @endif
                </div>
            </div>

            @if($product['description'])
            <p class="font-body-lg text-secondary leading-relaxed italic border-l-2 border-primary pl-6">
                "{{ $product['description'] }}"
            </p>
            @endif

            <form method="POST" action="{{ route('cart.add') }}" class="space-y-8">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <input type="hidden" name="product_variant_id" x-model="selectedVariantId">

                @if($variants->isNotEmpty())
                @php
                    $sizes = $variants->pluck('size')->unique()->filter();
                    $colors = $variants->pluck('color')->unique()->filter();
                @endphp
                <div>
                    @if($sizes->isNotEmpty())
                    <h4 class="font-label-caps text-[10px] text-primary mb-6 tracking-widest">SIZE GUIDE</h4>
                    <div class="flex gap-4 mb-8">
                        @foreach($sizes as $size)
                        <button type="button" 
                                @click="selectedSize = '{{ $size }}'; updateVariant()"
                                class="w-12 h-12 border border-primary rounded-full flex items-center justify-center font-label-caps text-[12px] hover:bg-primary hover:text-white transition-all"
                                :class="selectedSize === '{{ $size }}' ? 'bg-primary text-white' : ''">
                            {{ $size }}
                        </button>
                        @endforeach
                    </div>
                    @endif

                    @if($colors->isNotEmpty())
                    <h4 class="font-label-caps text-[10px] text-primary mb-6 tracking-widest">COLOR</h4>
                    <div class="flex gap-4 mb-8">
                        @foreach($colors as $color)
                        @php
                            $hex = $variants->firstWhere('color', $color)?->color_hex ?? '#ccc';
                        @endphp
                        <button type="button"
                                @click="selectedColor = '{{ $color }}'; updateVariant()"
                                class="w-10 h-10 rounded-full border-2 transition-all"
                                :class="selectedColor === '{{ $color }}' ? 'border-primary scale-110' : 'border-primary/30'"
                                style="background-color: {{ $hex }}"
                                title="{{ $color }}">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                @if(!$product['is_out_of_stock'])
                <div class="flex flex-col gap-4">
                    <div>
                        <h4 class="font-label-caps text-[10px] text-primary mb-4 tracking-widest">QUANTITY</h4>
                        <div class="flex items-center gap-4">
                            <button type="button" @click="qty = Math.max(1, qty - 1)" class="w-12 h-12 border border-primary rounded-full flex items-center justify-center font-label-caps hover:bg-primary hover:text-white transition-all">-</button>
                            <span class="font-headline-md text-xl text-primary w-8 text-center" x-text="qty">1</span>
                            <button type="button" @click="qty = Math.min(10, qty + 1)" class="w-12 h-12 border border-primary rounded-full flex items-center justify-center font-label-caps hover:bg-primary hover:text-white transition-all">+</button>
                            <input type="hidden" name="quantity" x-model.number="qty">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-primary text-white py-6 rounded-full font-label-caps tracking-widest hover:bg-primary-container transition-all shadow-lg border-0">ADD TO ARCHIVE</button>
                </div>
                @else
                <div class="bg-red-50 border border-red-200 p-8 rounded-[2rem] text-center">
                    <p class="font-headline-md text-red-800 mb-2">Currently Unavailable</p>
                    <p class="font-body-md text-red-600 text-sm">This artifact has been archived.</p>
                </div>
                @endif
            </form>

            <div class="pt-12 border-t border-primary/20 space-y-6">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 flex items-center justify-center border border-primary rounded-full">
                        <span class="material-symbols-outlined text-primary scale-90">eco</span>
                    </div>
                    <p class="font-body-md text-secondary text-sm italic">Carbon-Neutral Shipping Included</p>
                </div>
                @if($product['material'])
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 flex items-center justify-center border border-primary rounded-full">
                        <span class="material-symbols-outlined text-primary scale-90">inventory_2</span>
                    </div>
                    <p class="font-body-md text-secondary text-sm italic">Material: {{ $product['material'] }}</p>
                </div>
                @endif
            </div>
        </aside>
    </div>

    <!-- Related Products -->
    <section class="mt-32 border-t border-primary/10 pt-24 reveal-item">
        <h2 class="font-headline-md text-3xl text-primary mb-16">You may also appreciate</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            @foreach($relatedProducts as $rel)
            <a href="{{ $rel['slug'] ? url('/products/' . $rel['slug']) : url('/product/' . $rel['id']) }}" class="group block">
                <div class="border border-primary p-3 rounded-[2rem] bg-surface-container-low mb-6 overflow-hidden relative">
                    <div class="aspect-[3/4] rounded-[1.5rem] overflow-hidden">
                        <img src="{{ $rel['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="{{ $rel['name'] }}">
                    </div>
                </div>
                <h3 class="font-body-md text-primary font-bold">{{ $rel['name'] }}</h3>
                <p class="font-label-caps text-[10px] text-secondary mt-1">Rp {{ number_format($rel['price'], 0, ',', '.') }}</p>
            </a>
            @endforeach
        </div>
    </section>
</main>
@endsection

@push('js')
<script>
function productDetail() {
    return {
        selectedSize: '',
        selectedColor: '',
        selectedVariantId: null,
        selectedPrice: {{ $product['price'] }},
        qty: 1,
        variants: @json($variants),
        updateVariant() {
            const match = this.variants.find(v =>
                (!this.selectedSize || v.size === this.selectedSize) &&
                (!this.selectedColor || v.color === this.selectedColor)
            );
            this.selectedVariantId = match?.id ?? null;
            this.selectedPrice = match?.price ?? {{ $product['price'] }};
        }
    }
}
</script>
@endpush
