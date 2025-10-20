<?php

namespace Tests\Feature;

use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_paginated_sales_with_seller(): void
    {
        Sale::factory()->count(12)->create();

        $response = $this->getJson('/api/sales');

        $response->assertOk()
            ->assertJsonStructure([
                'current_page', 'data', 'first_page_url', 'from', 'last_page', 'last_page_url',
                'links', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to', 'total'
            ]);

        $this->assertArrayHasKey('seller', $response->json('data.0'));
    }

    public function test_store_creates_sale_and_returns_201(): void
    {
        $seller = Seller::factory()->create();

        $payload = [
            'amount' => 100.00,
            'sale_date' => now()->toDateString(),
            'seller_id' => $seller->id,
        ];

        $response = $this->postJson('/api/sales', $payload);

        $response->assertCreated()
            ->assertJson(['message' => 'Sale created successfully']);

        $this->assertDatabaseHas('sales', [
            'seller_id' => $seller->id,
            'amount' => 100.00,
            'commission' => 100.00 * config('commission.initial_percentage'),
        ]);
    }

    public function test_store_validates_input(): void
    {
        $response = $this->postJson('/api/sales', []);

        $response->assertUnprocessable()
            ->assertJsonStructure(['message', 'errors' => ['amount', 'sale_date', 'seller_id']]);
    }
}
