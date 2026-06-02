<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage products');
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage'])
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products   = $query->paginate(20)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $tags       = Tag::all();
        return view('admin.products.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'slug'               => 'nullable|string|unique:products,slug|max:255',
            'category_id'        => 'required|exists:categories,id',
            'sku'                => 'required|string|unique:products,sku',
            'price'              => 'required|numeric|min:0',
            'compare_price'      => 'nullable|numeric|min:0',
            'cost_price'         => 'nullable|numeric|min:0',
            'stock'              => 'required|integer|min:0',
            'low_stock_threshold'=> 'nullable|integer|min:0',
            'weight'             => 'nullable|numeric|min:0',
            'description'        => 'nullable|string',
            'short_description'  => 'nullable|string|max:500',
            'status'             => 'required|in:draft,published,archived',
            'is_limited_edition' => 'boolean',
            'is_featured'        => 'boolean',
            'images.*'           => 'image|max:2048',
            'tags'               => 'array',
            'tags.*'             => 'exists:tags,id',
        ]);

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'path'       => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        if ($request->filled('tags')) {
            $product->tags()->sync($request->tags);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dibuat.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $tags       = Tag::all();
        $product->load(['images', 'variants', 'tags']);
        return view('admin.products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'slug'               => 'nullable|string|unique:products,slug,' . $product->id . '|max:255',
            'category_id'        => 'required|exists:categories,id',
            'sku'                => 'required|string|unique:products,sku,' . $product->id,
            'price'              => 'required|numeric|min:0',
            'compare_price'      => 'nullable|numeric|min:0',
            'cost_price'         => 'nullable|numeric|min:0',
            'stock'              => 'required|integer|min:0',
            'low_stock_threshold'=> 'nullable|integer|min:0',
            'weight'             => 'nullable|numeric|min:0',
            'description'        => 'nullable|string',
            'short_description'  => 'nullable|string|max:500',
            'status'             => 'required|in:draft,published,archived',
            'is_limited_edition' => 'boolean',
            'is_featured'        => 'boolean',
            'images.*'           => 'image|max:2048',
            'tags'               => 'array',
            'tags.*'             => 'exists:tags,id',
        ]);

        $product->update($validated);

        if ($request->hasFile('images')) {
            $primarySet = false;
            $lastSort   = $product->images()->max('sort_order') ?? 0;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'path'       => $path,
                    'is_primary' => !$primarySet && !$product->primaryImage,
                    'sort_order' => $lastSort + $index + 1,
                ]);
                $primarySet = true;
            }
        }

        if ($request->filled('tags')) {
            $product->tags()->sync($request->tags);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produk dihapus.');
    }
}
