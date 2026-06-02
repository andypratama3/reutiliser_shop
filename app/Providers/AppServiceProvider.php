<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.landing', function ($view) {
            if (auth()->check()) {
                $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
            } else {
                $cart = Cart::firstOrCreate(['session_id' => session()->getId()]);
            }
            $cart->load('items.product.primaryImage', 'items.variant');
            $view->with('landingCart', $cart);
            $view->with('landingCategories', Category::where('is_active', true)->get());
        });
    }
}
