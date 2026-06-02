<footer class="bg-surface-container-high border-t border-outline-variant mt-16">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <a href="/" class="font-headline text-xl text-primary tracking-tight">
                    RÉUTILISER
                </a>
                <p class="font-body-md text-body-md text-on-surface-variant mt-4 max-w-xs">
                    Conscious Luxury for a Circular Future.
                </p>
            </div>
            <div>
                <h4 class="font-label-caps text-label-caps text-on-surface mb-4">Shop</h4>
                <ul class="space-y-2 font-body-md text-body-md">
                    <li><a href="{{ route('shop') }}" class="text-on-surface-variant hover:text-primary transition-colors">Semua Produk</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-caps text-label-caps text-on-surface mb-4">Bantuan</h4>
                <ul class="space-y-2 font-body-md text-body-md">
                    <li><a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Pengiriman</a></li>
                    <li><a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Returns</a></li>
                    <li><a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Kebijakan Privasi</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-outline-variant mt-8 pt-8 text-center font-body-md text-body-md text-on-surface-variant">
            &copy; {{ date('Y') }} RÉUTILISER. All rights reserved.
        </div>
    </div>
</footer>
