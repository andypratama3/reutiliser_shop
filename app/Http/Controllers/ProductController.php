<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Waitlist;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()
            ->with(['primaryImage', 'category'])
            ->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc'    => $query->orderBy('price'),
                'price_desc'   => $query->orderByDesc('price'),
                'newest'       => $query->orderByDesc('created_at'),
                'best_selling' => $query->orderByDesc('sold_count'),
                default        => null,
            };
        }

        $products   = $query->paginate(16)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::active()
            ->with(['images', 'variants', 'tags', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        $product->increment('view_count');

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }

    public function joinWaitlist(Request $request, Product $product)
    {
        $request->validate([
            'email' => 'required_without:phone|email|nullable',
            'phone' => 'required_without:email|string|nullable',
        ]);

        Waitlist::firstOrCreate([
            'product_id'         => $product->id,
            'product_variant_id' => $request->variant_id,
            'user_id'            => auth()->id(),
        ], [
            'email' => $request->email ?? auth()->user()?->email,
            'phone' => $request->phone ?? auth()->user()?->phone,
        ]);

        return back()->with('success', 'Berhasil masuk waitlist! Kami akan notifikasi kamu.');
    }
}
