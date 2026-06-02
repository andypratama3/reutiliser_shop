<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_shop_page_returns_200(): void
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
    }

    public function test_about_page_returns_200(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    public function test_journal_page_returns_200(): void
    {
        $response = $this->get('/journal');
        $response->assertStatus(200);
    }

    public function test_faq_page_returns_200(): void
    {
        $response = $this->get('/faq');
        $response->assertStatus(200);
    }

    public function test_contact_page_returns_200(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    public function test_sustainability_page_returns_200(): void
    {
        $response = $this->get('/sustainability');
        $response->assertStatus(200);
    }

    public function test_unauthenticated_checkout_redirects_to_login(): void
    {
        $response = $this->get('/checkout');
        $response->assertRedirect(route('login'));
    }
}
