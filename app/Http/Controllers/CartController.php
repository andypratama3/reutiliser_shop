<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product.primaryImage', 'items.variant');
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id'         => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity'           => 'required|integer|min:1|max:10',
        ]);

        $product = Product::active()->findOrFail($request->product_id);

        if ($product->isOutOfStock()) {
            return back()->with('error', 'Produk sedang habis stok.');
        }

        if ($request->product_variant_id) {
            $variant = ProductVariant::find($request->product_variant_id);
            if (!$variant || $variant->stock < 1) {
                return back()->with('error', 'Varian yang dipilih sedang habis stok.');
            }
        }

        $cart  = $this->getOrCreateCart();
        $price = $request->product_variant_id
            ? (ProductVariant::find($request->product_variant_id)?->price ?? $product->price)
            : $product->price;

        $cartItem = $cart->items()->where([
            'product_id'         => $product->id,
            'product_variant_id' => $request->product_variant_id,
        ])->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id'         => $product->id,
                'product_variant_id' => $request->product_variant_id,
                'quantity'           => $request->quantity,
                'price'              => $price,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:10']);

        $cart = $this->getOrCreateCart();
        if ($item->cart_id !== $cart->id) {
            abort(403);
        }

        $item->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(CartItem $item)
    {
        $cart = $this->getOrCreateCart();
        if ($item->cart_id !== $cart->id) {
            abort(403);
        }

        $item->delete();
        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    private function getOrCreateCart(): Cart
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }
        return Cart::firstOrCreate(['session_id' => session()->getId()]);
    }
}
