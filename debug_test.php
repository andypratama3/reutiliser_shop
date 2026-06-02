<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$app->setLocale('en');

// Migrate
$artisan = $app->make(Illuminate\Contracts\Console\Kernel::class);
$artisan->call('migrate:fresh', ['--env' => 'testing', '--force' => true, '--seed' => false]);
echo "Migration done\n";

try {
    $user = \App\Models\User::factory()->create();
    echo "User created: {$user->id}\n";

    $cat = \App\Models\Category::factory()->create();
    echo "Category created: {$cat->id}\n";

    $prod = \App\Models\Product::factory()->create([
        'category_id' => $cat->id,
        'price' => 150000,
        'stock' => 10,
        'status' => 'published',
    ]);
    echo "Product created: {$prod->id}\n";

    $cart = \App\Models\Cart::create(['user_id' => $user->id]);
    echo "Cart created: {$cart->id}\n";

    $item = $cart->items()->create([
        'product_id' => $prod->id,
        'quantity' => 2,
        'price' => 150000,
    ]);
    echo "CartItem created: {$item->id}\n";

    // Simulate auth
    auth()->login($user);

    // Try loading cart index view
    try {
        $cart->load('items.product.primaryImage', 'items.variant');
        $view = view('cart.index', ['cart' => $cart])->render();
        echo "Cart view rendered: " . strlen($view) . " chars\n";
    } catch (Exception $e) {
        echo "Cart view error: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString() . "\n";
    }

    // Try loading checkout index view
    $cart2 = \App\Models\Cart::where('user_id', $user->id)
        ->with('items.product.primaryImage', 'items.variant')
        ->firstOrFail();
    echo "Cart loaded for checkout\n";

    try {
        $addresses = $user->addresses;
        $view = view('checkout.index', compact('cart2', 'addresses'))->render();
        echo "Checkout view rendered: " . strlen($view) . " chars\n";
    } catch (Exception $e) {
        echo "Checkout view error: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString() . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
