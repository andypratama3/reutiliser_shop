@extends('layouts.landing')

@section('title', $post['title'] . ' | RÉUTILISER')

@section('content')
<article class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-12">
    <!-- Header -->
    <header class="max-w-4xl mx-auto text-center mb-16 reveal-item">
        <p class="font-label-caps text-secondary mb-6 tracking-widest">{{ $post['category'] }} / {{ $post['date'] }}</p>
        <h1 class="font-headline-lg text-headline-lg md:text-display-lg text-primary leading-tight mb-8">{{ $post['title'] }}</h1>
        <div class="flex items-center justify-center gap-4">
            <div class="w-12 h-px bg-primary"></div>
            <p class="font-label-caps text-[10px] text-primary uppercase">Words by Réutiliser Team</p>
            <div class="w-12 h-px bg-primary"></div>
        </div>
    </header>

    <!-- Hero Image -->
    <div class="aspect-video bg-secondary-container overflow-hidden mb-24 reveal-item">
        <img src="{{ $post['image'] }}" class="w-full h-full object-cover" alt="{{ $post['title'] }}">
    </div>

    <!-- Content -->
    <div class="max-w-3xl mx-auto reveal-item">
        <div class="prose prose-lg prose-primary max-w-none font-body-lg text-secondary leading-relaxed space-y-12">
            <p class="first-letter:text-7xl first-letter:font-display-lg first-letter:text-primary first-letter:mr-4 first-letter:float-left">
                {{ $post['excerpt'] }} Setiap helai kain menyimpan cerita panjang sebelum akhirnya tiba di tangan kita. Di Réutiliser, kami percaya bahwa mode sirkular bukan sekadar tren, melainkan sebuah gerakan untuk menghargai kembali setiap serat yang telah dihasilkan bumi.
            </p>

            <h2 class="font-headline-md text-headline-md text-primary">Menghidupkan Kembali yang Terlupakan</h2>
            <p>
                Proses upcycling yang kami lakukan dimulai dari penelusuran arsip tekstil, pemilihan material berkualitas, hingga rekonstruksi ulang oleh tangan-tangan artisan lokal. Setiap produk adalah hasil dari dialog antara masa lalu dan masa depan.
            </p>

            <blockquote class="border-l-4 border-primary pl-8 py-4 italic text-2xl text-primary font-headline-md">
                "Misi kami adalah membuktikan bahwa kemewahan tidak harus datang dengan mengorbankan lingkungan."
            </blockquote>

            <div class="grid grid-cols-2 gap-8 my-16">
                <img src="{{ $post['image'] }}" class="aspect-square object-cover stitch-border p-2" alt="Detail 1">
                <img src="{{ $post['image'] }}" class="aspect-square object-cover stitch-border p-2 grayscale" alt="Detail 2">
            </div>

            <p>
                Dengan memilih produk upcycling, Anda turut serta dalam gerakan mode berkelanjutan yang mengurangi limbah tekstil dan mendukung perekonomian lokal. Setiap pembelian adalah investasi untuk masa depan yang lebih hijau.
            </p>
        </div>

        <!-- Footer -->
        <footer class="mt-24 pt-12 border-t border-outline-variant flex justify-between items-center">
            <div class="flex gap-4">
                <span class="font-label-caps text-[10px] text-secondary">SHARE STORY:</span>
                <a href="#" class="font-label-caps text-[10px] text-primary hover:underline">TWITTER</a>
                <a href="#" class="font-label-caps text-[10px] text-primary hover:underline">LINKEDIN</a>
            </div>
            <a href="{{ url('/journal') }}" class="font-label-caps text-label-caps text-secondary inline-flex items-center gap-2 hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-sm">west</span> BACK TO JOURNAL
            </a>
        </footer>
    </div>
</article>
@endsection
