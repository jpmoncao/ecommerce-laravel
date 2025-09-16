<?php

namespace Tests\Feature;

use App\Models\ProductStockEntries;
use App\Models\ProductStocks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductStockEntriesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_should_list_product_stock_entries(): void
    {
        ProductStockEntries::factory()->create();

        $response = $this->get('/api/entries/stock');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson(['message' => 'Products stock entries listeds successfully!']);
    }

    public function test_should_list_product_stock_entry_by_id(): void
    {
        $productStockEntry = ProductStockEntries::factory()->create();

        $response = $this->get("/api/entries/stock/$productStockEntry->id_product_stock_entry");

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.id_product_stock_entry', $productStockEntry->id_product_stock_entry)
            ->assertJson(['message' => 'Product stock entry listed successfully!']);
    }

    public function test_should_create_product_stock_entry(): void
    {
        $variationData = [
            'product_variation_id' => ProductStocks::factory()->create()->id_stock,
            'quantity' => $this->faker->randomDigitNotNull()
        ];

        $response = $this->post('/api/entries/stock', $variationData);

        $this->assertDatabaseHas('product_stock_entries', ['product_variation_id' => $variationData['product_variation_id']]);

        $response
            ->assertStatus(201)
            ->assertJson(['message' => 'Product stock entry created successfully!'])
            ->assertJsonPath('data.product_variation_id', $variationData['product_variation_id']);
    }
}
