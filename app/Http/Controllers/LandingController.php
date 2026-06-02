<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function home()
    {
        $products = $this->getProductsData();
        $featuredProducts = collect($products)->take(3);
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
            ['name' => 'Julian Vause', 'role' => 'CREATIVE DIRECTOR', 'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=800&auto=format&fit=crop'],
            ['name' => 'Serafine Koh', 'role' => 'HEAD DESIGNER', 'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=800&auto=format&fit=crop'],
            ['name' => 'Marcus Chen', 'role' => 'SUSTAINABILITY LEAD', 'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=800&auto=format&fit=crop']
        ];
        return view('landing.about', compact('teamMembers'));
    }

    public function shop()
    {
        $products = $this->getProductsData();
        return view('landing.shop', compact('products'));
    }

    public function product($id)
    {
        $products = $this->getProductsData();
        $product = collect($products)->firstWhere('id', (int)$id);
        if (!$product) abort(404);
        $relatedProducts = collect($products)->where('id', '!=', (int)$id)->take(3);
        return view('landing.product-detail', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $allProducts = $this->getProductsData();
        $results = collect($allProducts)->filter(function($p) use ($query) {
            return empty($query) || str_contains(strtolower($p['name']), strtolower($query)) || str_contains(strtolower($p['category']), strtolower($query));
        });
        
        return view('landing.search-results', compact('results', 'query'));
    }

    public function wishlist()
    {
        $allProducts = $this->getProductsData();
        $wishlistItems = collect($allProducts)->take(2); // Dummy wishlist
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
            ['name' => 'CIRCULAR P.01', 'year' => '2024', 'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1200&auto=format&fit=crop'],
            ['name' => 'ARCHIVAL DEPOT', 'year' => '2023', 'image' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?q=80&w=1200&auto=format&fit=crop'],
            ['name' => 'INDIGO REBIRTH', 'year' => '2022', 'image' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=1200&auto=format&fit=crop']
        ];
        return view('landing.lookbook', compact('collections'));
    }

    public function faq()
    {
        $faqs = [
            ['q' => 'Where do you source your materials?', 'a' => 'We source exclusively from archival textile depots, vintage industrial collections, and discarded luxury remnants in Europe and Asia.'],
            ['q' => 'Are your pieces unique?', 'a' => 'Yes. Due to the nature of upcycling, no two items are ever identical. Each piece carries its own archival history.'],
            ['q' => 'Do you ship globally?', 'a' => 'We offer carbon-neutral shipping to over 50 countries worldwide.'],
            ['q' => 'What is your return policy?', 'a' => 'We offer a 14-day return policy for archival pieces, provided they are in their original reconstructed condition.']
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
        $cartItems = [['name' => 'Patchwork Archive Jacket', 'price' => 385, 'size' => 'MEDIUM', 'note' => 'UNIQUE PIECE', 'qty' => 1, 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?q=80&w=400&auto=format&fit=crop']];
        $shippingMethods = [
            ['id' => 'standard', 'name' => 'Standard Carbon-Neutral', 'price' => 0, 'time' => '5-7 business days', 'icon' => 'local_shipping'],
            ['id' => 'express', 'name' => 'Express Priority', 'price' => 25, 'time' => '1-2 business days', 'icon' => 'bolt'],
            ['id' => 'global', 'name' => 'Global Concierge', 'price' => 50, 'time' => '3-5 business days', 'icon' => 'public']
        ];
        $subtotal = 385; $shipping = 0; $total = $subtotal + $shipping;
        return view('landing.checkout', compact('cartItems', 'subtotal', 'shipping', 'total', 'shippingMethods'));
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
        return [
            ['id' => 1, 'name' => 'Patchwork Archive Jacket', 'material' => 'Upcycled Cotton & Denim', 'price' => 385, 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?q=80&w=800&auto=format&fit=crop', 'tag' => 'BEST SELLER', 'category' => 'Jackets', 'description' => 'A singular masterpiece of circular design. Constructed from over 15 unique swatches of archival denim.'],
            ['id' => 2, 'name' => 'Panelled Denim Trouser', 'material' => "Vintage Repurposed Levi's", 'price' => 210, 'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?q=80&w=800&auto=format&fit=crop', 'tag' => 'LIMITED', 'category' => 'Trousers', 'description' => 'Reconstructed from vintage Levi\'s 501s with architectural paneling.'],
            ['id' => 3, 'name' => 'Bone Linen Overshirt', 'material' => '100% Deadstock Linen', 'price' => 175, 'image' => 'https://images.unsplash.com/photo-1598033129183-c4f50c7176c8?q=80&w=800&auto=format&fit=crop', 'tag' => 'NEW', 'category' => 'Shirts', 'description' => 'Cut from premium deadstock Belgian linen for a soft, architectural silhouette.'],
            ['id' => 4, 'name' => 'Archival Forest Blazer', 'material' => 'Vintage Wool Blend', 'price' => 420, 'image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=800&auto=format&fit=crop', 'tag' => 'EXCLUSIVE', 'category' => 'Jackets', 'description' => 'Meticulously tailored from archival wool.'],
            ['id' => 5, 'name' => 'Studio Utility Tote', 'material' => 'Reinforced Canvas Scraps', 'price' => 130, 'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=800&auto=format&fit=crop', 'tag' => null, 'category' => 'Accessories', 'description' => 'A durable tote crafted from workshop remnants.'],
            ['id' => 6, 'name' => 'Recycled Cotton Knit', 'material' => 'Hand-knit / Circular Yarn', 'price' => 155, 'image' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?q=80&w=800&auto=format&fit=crop', 'tag' => 'LIMITED', 'category' => 'Shirts', 'description' => 'Soft, breathable knit from circular fibers.']
        ];
    }

    private function getJournalData()
    {
        return [
            ['slug' => 'lifecycle-denim-thread', 'title' => 'The Lifecycle of a Denim Thread', 'date' => 'MAY 24, 2024', 'category' => 'PROCESS', 'excerpt' => 'Exploring the journey of repurposed indigo from archival discovery to artisan reconstruction.', 'image' => 'https://images.unsplash.com/photo-1582562124811-c09040d0a901?q=80&w=800&auto=format&fit=crop'],
            ['slug' => 'local-artisans-circularity', 'title' => 'Local Artisans: The Heart of Circularity', 'date' => 'JUNE 02, 2024', 'category' => 'PEOPLE', 'excerpt' => 'A closer look at the craftsmen and women breathing new life into forgotten textiles.', 'image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=800&auto=format&fit=crop']
        ];
    }
}
