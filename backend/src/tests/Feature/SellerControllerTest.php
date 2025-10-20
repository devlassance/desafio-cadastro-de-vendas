<?php

namespace Tests\Feature;

use App\Mail\DailySellerSalesSummary;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SellerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_paginated_sellers(): void
    {
        Seller::factory()->count(15)->create();

        $response = $this->getJson('/api/sellers');

        $response->assertOk()
            ->assertJsonStructure([
                'current_page', 'data', 'first_page_url', 'from', 'last_page', 'last_page_url',
                'links', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to', 'total'
            ]);
    }

    public function test_store_creates_seller_and_returns_201(): void
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $response = $this->postJson('/api/sellers', $payload);

        $response->assertCreated()
            ->assertJson(['message' => 'Seller created successfully']);

        $this->assertDatabaseHas('sellers', $payload);
    }

    public function test_store_validates_input(): void
    {
        $response = $this->postJson('/api/sellers', []);

        $response->assertUnprocessable()
            ->assertJsonStructure(['message', 'errors' => ['name', 'email']]);
    }

    public function test_show_returns_seller_with_sales(): void
    {
        $seller = Seller::factory()->create();

        $response = $this->getJson("/api/sellers/{$seller->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $seller->id,
                'name' => $seller->name,
                'email' => $seller->email,
            ]);
    }

    public function test_show_returns_404_when_seller_not_found(): void
    {
        $response = $this->getJson('/api/sellers/999999');
        $response->assertNotFound()
            ->assertJson(['message' => 'Seller not found.']);
    }

    public function test_show_for_select_returns_collection(): void
    {
        Seller::factory()->count(3)->create();

        $response = $this->getJson('/api/sellers/for-select');

        $response->assertOk();
        $this->assertIsArray($response->json());
        $this->assertNotEmpty($response->json());
    }
}
