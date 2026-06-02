@extends('layouts.landing')

@section('title', 'Shop All | RÉUTILISER Conscious Luxury')

@section('content')
<main class="max-w-container-max mx-auto px-4 md:px-12 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Sidebar Filters -->
        <aside class="lg:col-span-3 space-y-12 reveal-item border border-primary p-8 rounded-[2rem] bg-surface h-fit lg:sticky lg:top-32">
            <div>
                <h3 class="font-label-caps text-primary mb-6 border-b border-primary/20 pb-2 text-[12px] tracking-widest">CATEGORIES</h3>
                <ul class="space-y-4">
                    <li><button class="font-body-md text-primary font-bold">Shop All</button></li>
                    <li><button class="font-body-md text-secondary hover:text-primary transition-colors">Jackets</button></li>
                    <li><button class="font-body-md text-secondary hover:text-primary transition-colors">Trousers</button></li>
                    <li><button class="font-body-md text-secondary hover:text-primary transition-colors">Shirts</button></li>
                </ul>
            </div>

            <div>
                <h3 class="font-label-caps text-primary mb-6 border-b border-primary/20 pb-2 text-[12px] tracking-widest">PRICE RANGE</h3>
                <div class="space-y-3">
                    @foreach(['Under $200', '$200 - $400', 'Above $400'] as $range)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" class="w-4 h-4 rounded-full border-primary text-primary focus:ring-primary">
                        <span class="font-body-md text-secondary group-hover:text-primary transition-colors">{{ $range }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Product Listing -->
        <div class="lg:col-span-9">
            <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6 reveal-item">
                <p class="font-label-caps text-secondary text-[10px] tracking-[0.2em]">{{ count($products) }} UNIQUE ARTIFACTS</p>
                <div class="flex items-center gap-4">
                    <span class="font-label-caps text-[10px] text-secondary">SORT:</span>
                    <select class="bg-transparent border border-primary rounded-full px-6 py-2 font-label-caps text-primary focus:ring-1 focus:ring-primary focus:outline-none cursor-pointer text-[12px] tracking-widest">
                        <option>Latest Drops</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-12">
                @foreach($products as $product)
                <div class="group reveal-item">
                    <div class="border border-primary p-3 rounded-[2.5rem] bg-surface-container-low mb-6 relative overflow-hidden">
                        <div class="aspect-[3/4] rounded-[2rem] overflow-hidden">
                            <img src="{{ $product['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[1500ms]" alt="{{ $product['name'] }}">
                        </div>
                        <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                            <a href="{{ url('/product/' . $product['id']) }}" class="w-full bg-white text-primary py-4 rounded-full font-label-caps text-center text-[10px] tracking-widest shadow-xl border border-primary">VIEW PIECE</a>
                        </div>
                    </div>
                    <div class="px-4">
                        <h3 class="font-body-lg text-primary font-bold">{{ $product['name'] }}</h3>
                        <p class="font-label-caps text-[10px] text-secondary mt-1 uppercase">{{ $product['material'] }}</p>
                        <p class="font-body-md text-primary mt-2 font-bold">${{ number_format($product['price'], 0) }}</p>
                    </div>
                </div>
                @endforeach
            </section>
        </div>
    </div>
</main>
@endsection
