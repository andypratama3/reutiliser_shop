<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $this->user = User::factory()->create();
        $category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 150000,
            'stock' => 10,
            'status' => 'published',
        ]);
    }

    public function test_guest_can_view_cart(): void
    {
        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_view_cart(): void
    {
        $response = $this->actingAs($this->user)->get(route('cart.index'));
        $response->assertStatus(200);
    }

    public function test_guest_can_add_to_cart(): void
    {
        $response = $this->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
    }

    public function test_authenticated_user_can_add_to_cart(): void
    {
        $response = $this->actingAs($this->user)->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
    }

    public function test_cart_shows_added_items(): void
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        $cart->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->user)->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertSee($this->product->name);
    }

    public function test_empty_cart_shows_empty_message(): void
    {
        $response = $this->actingAs($this->user)->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertSee('Your archive is currently empty.');
    }

    public function test_out_of_stock_product_cannot_be_added(): void
    {
        $this->product->update(['stock' => 0]);

        $response = $this->actingAs($this->user)->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
