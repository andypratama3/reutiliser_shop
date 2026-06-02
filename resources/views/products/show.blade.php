@extends('layouts.landing')

@section('title', $product->name . ' | RÉUTILISER')

@section('content')

<div class="max-w-[1440px] mx-auto px-6 md:px-12 pb-12 lg:pb-24" x-data="productApp()">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
        <!-- Product Images -->
        <div class="lg:col-span-7 space-y-10">
            <div class="reveal-item">
                <div class="aspect-[3/4] bg-[#f3f0ef] rounded-[2.5rem] overflow-hidden shadow-sm relative group border border-primary/5">
                    @php
                        $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                    @endphp
                    @if($primaryImage)
                        <img src="{{ $primaryImage->url }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[3000ms]"
                             x-ref="mainImage"
                             onerror="this.style.display='none'; this.parentElement.querySelector('.placeholder-fallback').classList.remove('hidden'); this.parentElement.querySelector('.placeholder-fallback').classList.add('flex')">
                        <div class="placeholder-fallback absolute inset-0 hidden items-center justify-center bg-surface-container-highest text-primary/20">
                            <div class="text-center">
                                <span class="material-symbols-outlined text-[100px] mb-4">image</span>
                                <p class="font-label-caps text-[10px] tracking-widest opacity-40">IMAGE UNAVAILABLE</p>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-surface-container-highest text-primary/10">
                            <span class="material-symbols-outlined text-[100px] mb-4">image</span>
                            <p class="font-label-caps text-[10px] tracking-widest opacity-40">NO ARCHIVAL IMAGE</p>
                        </div>
                    @endif
                    
                    @if($product->is_limited_edition)
                        <div class="absolute top-8 left-8">
                            <span class="bg-primary text-white font-label-caps text-[8px] px-6 py-2.5 rounded-full tracking-[0.4em] shadow-xl backdrop-blur-md bg-primary/90 border-0">ARCHIVAL SPECIMEN</span>
                        </div>
                    @endif
                </div>
            </div>

            @if($product->images->count() > 1)
                <div class="grid grid-cols-4 gap-4 lg:gap-6 reveal-item">
                    @foreach($product->images as $image)
                        <button class="aspect-square bg-white rounded-2xl overflow-hidden border border-primary/10 hover:border-primary transition-all p-1 shadow-sm group"
                                @click="$refs.mainImage.src = '{{ $image->url }}'">
                            <img src="{{ $image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-xl group-hover:scale-110 transition-transform duration-700">
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- Technical Data / Provenance (Desktop) -->
            <div class="hidden lg:block pt-20 space-y-12 reveal-item">
                <div class="grid grid-cols-2 gap-12 border-t border-primary/10 pt-12">
                    <div>
                        <h4 class="font-label-caps text-[9px] text-secondary mb-4 tracking-[0.4em] uppercase opacity-40">Composition</h4>
                        <p class="font-body-lg text-primary text-lg leading-relaxed italic">
                            {{ $product->material ?? 'Archival luxury remnants reconstructed for radical circularity.' }}
                        </p>
                    </div>
                    <div>
                        <h4 class="font-label-caps text-[9px] text-secondary mb-4 tracking-[0.4em] uppercase opacity-40">Object Identification</h4>
                        <p class="font-body-md text-primary font-bold tracking-[0.2em] text-base uppercase">REF: {{ $product->sku ?? 'ARC-' . str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info Sidebar -->
        <aside class="lg:col-span-5 reveal-item lg:sticky lg:top-40 h-fit">
            <div class="bg-white border border-primary p-8 lg:p-12 rounded-[2.5rem] shadow-sm space-y-10 lg:space-y-12">
                <div class="space-y-6">
                    <nav class="flex items-center gap-3 opacity-30">
                        <a href="{{ route('shop') }}" class="font-label-caps text-[9px] tracking-[0.2em] uppercase hover:text-primary transition-colors border-0">The Archives</a>
                        <span class="text-[8px] opacity-40">/</span>
                        <span class="font-label-caps text-[9px] tracking-[0.2em] uppercase text-primary font-bold">{{ $product->category?->name ?? 'Artifact' }}</span>
                    </nav>

                    <h1 class="font-display-lg text-primary leading-[0.9] uppercase tracking-tighter text-5xl lg:text-7xl break-normal whitespace-normal">{{ $product->name }}</h1>
                    
                    <div class="flex items-baseline gap-6">
                        <p class="font-headline-md text-primary text-3xl lg:text-4xl font-bold">
                            <span x-text="'Rp ' + Number(selectedPrice).toLocaleString('id-ID')">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </p>
                        @if($product->compare_price)
                            <p class="font-headline-md text-secondary text-xl line-through opacity-30">Rp {{ number_format($product->compare_price, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-10">
                    @if($product->short_description)
                        <p class="font-body-lg text-secondary leading-relaxed italic text-xl opacity-70 border-l-2 border-primary/20 pl-8">
                            "{{ $product->short_description }}"
                        </p>
                    @endif

                    @if(!$product->isOutOfStock())
                        <form method="POST" action="{{ route('cart.add') }}" class="space-y-10">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="product_variant_id" x-model="variantId">

                            @if($product->variants->isNotEmpty())
                                @php
                                    $sizes = $product->variants->pluck('size')->unique()->filter();
                                    $colors = $product->variants->pluck('color')->unique()->filter();
                                @endphp

                                <div class="space-y-10">
                                    @if($sizes->isNotEmpty())
                                        <div>
                                            <h4 class="font-label-caps text-[9px] text-primary mb-6 tracking-[0.3em] uppercase font-bold">SIZE SELECTION</h4>
                                            <div class="flex flex-wrap gap-3">
                                                @foreach($sizes as $size)
                                                    <button type="button" 
                                                            @click="selectedSize = '{{ $size }}'; updateVariant()"
                                                            class="w-12 h-12 rounded-full border border-primary flex items-center justify-center font-label-caps text-[11px] transition-all hover:bg-primary hover:text-white font-bold border-1 bg-white text-primary"
                                                            :class="selectedSize === '{{ $size }}' ? 'bg-primary text-white' : 'bg-white text-primary'">
                                                        {{ $size }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if($colors->isNotEmpty())
                                        <div>
                                            <h4 class="font-label-caps text-[9px] text-primary mb-6 tracking-[0.3em] uppercase font-bold">ARCHIVAL TINT</h4>
                                            <div class="flex flex-wrap gap-5">
                                                @foreach($colors as $color)
                                                    @php
                                                        $variant = $product->variants->firstWhere('color', $color);
                                                        $hex = $variant?->color_hex ?? '#ccc';
                                                    @endphp
                                                    <button type="button"
                                                            @click="selectedColor = '{{ $color }}'; updateVariant()"
                                                            class="w-10 h-10 rounded-full border-2 transition-all"
                                                            :class="selectedColor === '{{ $color }}' ? 'border-primary scale-110' : 'border-primary/20'"
                                                            style="background-color: {{ $hex }}"
                                                            title="{{ $color }}">
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="space-y-8">
                                <div class="flex items-center justify-between border-t border-primary/10 pt-8">
                                    <h4 class="font-label-caps text-[9px] text-primary tracking-[0.3em] uppercase font-bold">QUANTITY</h4>
                                    <div class="flex items-center gap-6">
                                        <button type="button" @click="qty = Math.max(1, qty - 1)" class="w-10 h-10 flex items-center justify-center border border-primary rounded-full text-primary hover:bg-primary hover:text-white transition-all bg-transparent">
                                            <span class="material-symbols-outlined text-sm">remove</span>
                                        </button>
                                        <span class="font-display-lg text-xl text-primary w-6 text-center" x-text="qty">1</span>
                                        <button type="button" @click="qty = Math.min(10, qty + 1)" class="w-10 h-10 flex items-center justify-center border border-primary rounded-full text-primary hover:bg-primary hover:text-white transition-all bg-transparent">
                                            <span class="material-symbols-outlined text-sm">add</span>
                                        </button>
                                        <input type="hidden" name="quantity" x-model.number="qty">
                                    </div>
                                </div>
                                
                                <button type="submit" class="w-full bg-primary text-white py-6 rounded-full font-label-caps tracking-[0.3em] text-[10px] font-bold hover:bg-primary-container transition-all shadow-xl border-0 uppercase">
                                    Add to Archive
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="space-y-8">
                            <div class="bg-red-50/50 border border-red-100 p-8 rounded-[2rem] text-center">
                                <p class="font-display-lg text-red-900 text-2xl mb-2 uppercase">Sold to Archive</p>
                                <p class="font-body-md text-red-700/60 text-xs italic">This piece is currently being curated elsewhere.</p>
                            </div>

                            <div class="bg-primary/5 p-8 rounded-[2rem] border border-primary/5">
                                <h4 class="font-label-caps text-[9px] text-primary mb-6 tracking-[0.3em] uppercase font-bold">Priority Notify</h4>
                                <form method="POST" action="{{ route('products.waitlist', $product) }}" class="space-y-4">
                                    @csrf
                                    <div class="bg-white rounded-xl p-1.5 flex gap-2 border border-primary/10 focus-within:border-primary/30 transition-all">
                                        <input type="email" name="email" placeholder="EMAIL" value="{{ auth()->user()?->email }}"
                                               class="bg-transparent border-none w-full focus:ring-0 text-[10px] text-primary px-4 font-bold" required>
                                        <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg font-label-caps text-[8px] tracking-widest hover:bg-primary-container transition-all uppercase border-0">Notify</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="pt-8 border-t border-primary/10 flex gap-6">
                    <div class="flex-1 flex flex-col items-center gap-2 opacity-40 grayscale hover:grayscale-0 hover:opacity-100 transition-all text-center">
                        <span class="material-symbols-outlined text-2xl">eco</span>
                        <span class="text-[7px] tracking-[0.2em] uppercase font-bold">Circular</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-2 opacity-40 grayscale hover:grayscale-0 hover:opacity-100 transition-all text-center">
                        <span class="material-symbols-outlined text-2xl">history_edu</span>
                        <span class="text-[7px] tracking-[0.2em] uppercase font-bold">Provenance</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-2 opacity-40 grayscale hover:grayscale-0 hover:opacity-100 transition-all text-center">
                        <span class="material-symbols-outlined text-2xl">local_shipping</span>
                        <span class="text-[7px] tracking-[0.2em] uppercase font-bold">Tracked</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    @if($related->isNotEmpty())
        <section class="mt-32 border-t border-primary/10 pt-24 reveal-item">
            <div class="flex justify-between items-end mb-16">
                <div>
                    <p class="font-label-caps text-[9px] text-primary tracking-[0.4em] mb-4 uppercase font-bold opacity-30">Exploration</p>
                    <h2 class="font-display-lg text-primary text-4xl lg:text-5xl uppercase tracking-tighter">You may also appreciate</h2>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                @foreach($related as $rel)
                    <a href="{{ route('products.show', $rel->slug) }}" class="group block">
                        <div class="bg-white p-4 rounded-[2.5rem] border border-primary/10 mb-8 overflow-hidden relative shadow-sm hover:shadow-2xl transition-all">
                            <div class="aspect-[3/4] rounded-[1.5rem] overflow-hidden">
                                <img src="{{ $rel->primary_image_url }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2000ms]" 
                                     alt="{{ $rel->name }}">
                            </div>
                        </div>
                        <h3 class="font-display-lg text-primary font-bold text-xl group-hover:italic transition-all tracking-tight leading-tight">{{ $rel->name }}</h3>
                        <p class="font-label-caps text-[10px] text-secondary opacity-40 tracking-widest uppercase font-bold">Rp {{ number_format($rel->price, 0, ',', '.') }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@push('js')
<script>
function productApp() {
    return {
        selectedSize: '',
        selectedColor: '',
        variantId: null,
        selectedPrice: {{ $product->price }},
        qty: 1,
        variants: @json($product->variants),
        updateVariant() {
            if (this.variants.length > 0) {
                const match = this.variants.find(v =>
                    (!this.selectedSize || v.size === this.selectedSize) &&
                    (!this.selectedColor || v.color === this.selectedColor)
                );
                this.variantId = match?.id ?? null;
                this.selectedPrice = match?.price ?? {{ $product->price }};
            }
        }
    }
}
</script>
@endpush
