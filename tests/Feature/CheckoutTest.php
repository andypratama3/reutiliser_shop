<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Cart $cart;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $this->user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 150000,
            'stock' => 10,
            'status' => 'published',
        ]);

        $this->cart = Cart::create(['user_id' => $this->user->id]);
        $this->cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 150000,
        ]);
    }

    public function test_authenticated_user_can_access_checkout_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('checkout.index'));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_checkout_page(): void
    {
        $response = $this->get(route('checkout.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_checkout_redirect_works(): void
    {
        $response = $this->actingAs($this->user)->get('/checkout');
        $response->assertRedirect(route('checkout.index'));
    }

    public function test_checkout_page_shows_cart_items(): void
    {
        $response = $this->actingAs($this->user)->get(route('checkout.index'));
        $response->assertStatus(200);
        $response->assertSee($this->cart->items->first()->product->name);
    }

    public function test_empty_cart_redirects_from_checkout(): void
    {
        $this->cart->items()->delete();
        $response = $this->actingAs($this->user)->get(route('checkout.index'));
        $response->assertRedirect(route('cart.index'));
    }
}
