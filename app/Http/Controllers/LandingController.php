<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function home()
    {
        $products = $this->getProductsData();
        $featuredProducts = $products->where('is_featured', true)->take(3)->values()->map(fn($p) => $this->mapProduct($p));
        $testimonials = [
            ['quote' => "The quality of the upcycled denim is unlike anything I've seen. It feels like wearing a piece of history.", 'author' => 'Elena Rodriguez', 'role' => 'Sustainable Stylist'],
            ['quote' => "Finally, a brand that marries luxury aesthetics with genuine environmental radicalism.", 'author' => 'Marcus Thorne', 'role' => 'Archival Collector']
        ];
        $journalPosts = collect($this->getJournalData())->take(2);

        return view('landing.home', compact('featuredProducts', 'testimonials', 'journalPosts'));
    }

    public function about()
    {
        $teamMembers = [
            ['name' => 'Sofyan Hadi', 'role' => 'CHIEF EXECUTIVE OFFICER (CEO)', 'image' => 'assets_landing/team/Sofyan H.png'],
            ['name' => 'Chelsea L', 'role' => 'HEAD OF DESIGN', 'image' => 'assets_landing/team/Chelsea laurent T. b..png'],
            ['name' => 'Ismi Maulidia', 'role' => 'CHIEF OPERATING OFFICER (COO)', 'image' => 'assets_landing/team/ISMI MAULIDIA.png'],
            ['name' => 'Hanny Erviana', 'role' => 'CHIEF MARKETING OFFICER (CMO)', 'image' => 'assets_landing/team/Hanny erviana zahra.png']
        ];
        return view('landing.about', compact('teamMembers'));
    }

    public function shop(Request $request)
    {
        $query = Product::with('primaryImage', 'category', 'tags')->active()->latest();

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('short_description', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('price_range')) {
            $parts = explode('-', $request->price_range);
            if (count($parts) === 2) {
                $request->merge(['price_min' => $parts[0], 'price_max' => $parts[1]]);
            }
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', (int) $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (int) $request->price_max);
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc'  => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'newest'     => $query->orderByDesc('created_at'),
                default      => null,
            };
        }

        $paginator = $query->paginate(12)->withQueryString();
        $products  = $paginator->map(fn($p) => $this->mapProduct($p));
        $categories = Category::where('is_active', true)->get();
        $selectedCategory = $request->category;

        return view('landing.shop', compact('products', 'paginator', 'categories', 'selectedCategory'));
    }

    public function product($id)
    {
        $productModel = Product::with('primaryImage', 'images', 'variants', 'category', 'tags')->active()->findOrFail($id);
        $mapped = $this->mapProduct($productModel);
        $relatedProducts = Product::with('primaryImage')
            ->active()
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(fn($p) => $this->mapProduct($p));
        return view('landing.product-detail', [
            'product' => $mapped,
            'productModel' => $productModel,
            'variants' => $productModel->variants,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $allProducts = $this->getProductsData();
        $results = $allProducts->filter(function ($p) use ($query) {
            return empty($query)
                || str_contains(strtolower($p->name), strtolower($query))
                || str_contains(strtolower($p->category?->name ?? ''), strtolower($query));
        })->values()->map(fn($p) => $this->mapProduct($p));

        return view('landing.search-results', compact('results', 'query'));
    }

    public function wishlist()
    {
        $allProducts = $this->getProductsData();
        $wishlistItems = $allProducts->take(2)->values()->map(fn($p) => $this->mapProduct($p));
        return view('landing.wishlist', compact('wishlistItems'));
    }

    public function journal()
    {
        $posts = $this->getJournalData();
        return view('landing.journal-index', compact('posts'));
    }

    public function journalSingle($slug)
    {
        $posts = $this->getJournalData();
        $post = collect($posts)->firstWhere('slug', $slug);
        if (!$post) abort(404);
        return view('landing.journal-single', compact('post'));
    }

    public function lookbook()
    {
        $collections = [
            ['name' => 'CIRCULAR P.01', 'year' => '2026', 'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1200&auto=format&fit=crop'],
            ['name' => 'ARCHIVAL DEPOT', 'year' => '2025', 'image' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?q=80&w=1200&auto=format&fit=crop'],
            ['name' => 'INDIGO REBIRTH', 'year' => '2025', 'image' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=1200&auto=format&fit=crop']
        ];
        return view('landing.lookbook', compact('collections'));
    }

    public function faq()
    {
        $faqs = [
            ['q' => 'Dari mana kalian mendapatkan material?', 'a' => 'Kami mengumpulkan material dari depot tekstil arsip, koleksi industri vintage, dan sisa material mewah dari berbagai sumber di Indonesia dan Asia.'],
            ['q' => 'Apakah setiap produk unik?', 'a' => 'Ya. Karena sifat upcycling, tidak ada dua item yang identik. Setiap produk membawa sejarah arsipnya sendiri.'],
            ['q' => 'Apakah bisa kirim ke seluruh Indonesia?', 'a' => 'Kami menawarkan pengiriman ke seluruh Indonesia melalui berbagai jasa kurir terpercaya.'],
            ['q' => 'Bagaimana kebijakan pengembalian?', 'a' => 'Kami menyediakan kebijakan pengembalian 14 hari untuk produk arsip, dengan syarat produk masih dalam kondisi asli.']
        ];
        return view('landing.faq', compact('faqs'));
    }

    public function contact()
    {
        return view('landing.contact');
    }

    public function sustainability()
    {
        return view('landing.sustainability');
    }

    public function checkout()
    {
        // If the user is not authenticated, send them to login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // For authenticated users, redirect to the auth-protected checkout flow
        // The dedicated CheckoutController@index is available at route named 'checkout.index'
        return redirect()->route('checkout.index');
    }

    public function success()
    {
        return view('landing.success');
    }

    public function legal($type)
    {
        $title = $type == 'privacy' ? 'Privacy Policy' : 'Terms of Service';
        return view('landing.legal', compact('title'));
    }

    private function getProductsData()
    {
        return Product::with('primaryImage', 'category', 'tags')->active()->latest()->get();
    }

    private function mapProduct($product)
    {
        $firstTag = $product->tags?->first();
        $tagNames = [
            'best-seller' => 'BEST SELLER',
            'limited-edition' => 'LIMITED',
            'baru' => 'NEW',
            'diskon' => 'SALE',
        ];

        $imagePath = $product->primaryImage?->path;
        if ($imagePath) {
            $imageUrl = str_starts_with($imagePath, 'http') ? $imagePath : \Illuminate\Support\Facades\Storage::url($imagePath);
        } else {
            $imageUrl = 'https://placehold.co/600x600/e2e8f0/64748b?text=No+Image';
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'material' => $product->material ?? 'Mixed Materials',
            'price' => (int) $product->price,
            'compare_price' => $product->compare_price ? (int) $product->compare_price : null,
            'image' => $imageUrl,
            'tag' => $firstTag ? ($tagNames[$firstTag->slug] ?? strtoupper($firstTag->name)) : null,
            'category' => $product->category?->name ?? 'General',
            'category_slug' => $product->category?->slug ?? '',
            'description' => $product->short_description ?? $product->description ?? '',
            'stock' => $product->stock,
            'is_out_of_stock' => $product->isOutOfStock(),
        ];
    }

    private function getJournalData()
    {
        return [
            ['slug' => 'lifecycle-denim-thread', 'title' => 'Siklus Hidup Sebuah Benang Denim', 'date' => 'MEI 24, 2026', 'category' => 'PROSES', 'excerpt' => 'Menelusuri perjalanan indigo yang didaur ulang dari penemuan arsip hingga rekonstruksi oleh artisan.', 'image' => 'https://images.unsplash.com/photo-1582562124811-c09040d0a901?q=80&w=800&auto=format&fit=crop'],
            ['slug' => 'local-artisans-circularity', 'title' => 'Artisan Lokal: Jantung dari Sirkularitas', 'date' => 'JUNI 02, 2026', 'category' => 'PROFIL', 'excerpt' => 'Melihat lebih dekat para pengrajin yang menghidupkan kembali tekstil-tekstil yang terlupakan.', 'image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=800&auto=format&fit=crop']
        ];
    }
}
