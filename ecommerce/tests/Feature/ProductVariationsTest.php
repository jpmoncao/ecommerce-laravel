<?php

namespace Tests\Feature;

use App\Models\Products;
use App\Models\ProductStockEntries;
use App\Models\ProductVariations;
use App\Models\ProductStocks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProductVariationsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private const INVALID_PRODUCT_VARIATION_ID = 'invalid_id';

    public static function notFoundProvider(): array
    {
        return [
            'product variation not found' => ['/api/variations/' . self::INVALID_PRODUCT_VARIATION_ID, 'Product variation not found!'],
            'product variation not found' => ['/api/variations/' . self::INVALID_PRODUCT_VARIATION_ID . '/product', 'Product variation not found!'],
            'product variation not found' => ['/api/variations/' . self::INVALID_PRODUCT_VARIATION_ID . '/stock', 'Product variation not found!'],
            'product variation not found' => ['/api/variations/' . self::INVALID_PRODUCT_VARIATION_ID . '/entries/stock', 'Product variation not found!'],
        ];
    }

    public function test_should_list_product_variation_by_id(): void
    {
        $variation = ProductVariations::factory()->create();

        $response = $this->get("/api/variations/{$variation->id_product_variation}");

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Product variation listed successfully!'])
            ->assertJsonPath('data.product_id', $variation->product_id);
    }

    public function test_should_create_variation_of_product(): void
    {
        $variationData = [
            'variation' => $this->faker->word(),
            'amount' => $this->faker->randomFloat(2, 0),
            'product_id' => Products::factory()->create()->id_product,
        ];

        $response = $this->post('/api/variations/', $variationData);

        $this->assertDatabaseHas('product_variations', ['product_id' => $variationData['product_id']]);

        $response
            ->assertStatus(201)
            ->assertJson(['message' => 'Product variation created successfully!'])
            ->assertJsonPath('data.product_id', $variationData['product_id']);
    }

    public function test_should_update_variation_of_product(): void
    {
        $variation = ProductVariations::factory()->create();
        $VARIATION_TO_CHANGE = $this->faker->word();

        $response = $this->put("/api/variations/{$variation->id_product_variation}", [
            'variation' => $VARIATION_TO_CHANGE
        ]);

        $this->assertDatabaseHas('product_variations', [
            'id_product_variation' => $variation->id_product_variation,
            'variation' => $VARIATION_TO_CHANGE
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Product variation updated successfully!'])
            ->assertJsonPath('data.product_id', $variation->product_id);
    }

    public function test_should_delete_variation_of_product(): void
    {
        $variation = ProductVariations::factory()->create();

        $response = $this->delete("/api/variations/{$variation->id_product_variation}");

        $this->assertDatabaseMissing('product_variations', ['id_product_variation' => $variation->id_product_variation]);

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Product variation deleted successfully!']);
    }

    public function test_should_list_product_with_product_variation_id(): void
    {
        $variation = ProductVariations::factory()->create();

        $response = $this->get("/api/variations/{$variation->id_product_variation}/product");

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.id_product', $variation->product_id);
    }

    public function test_should_list_variation_stock_with_product_variation_id(): void
    {
        $stock = ProductStocks::factory()->create();

        $response = $this->get("/api/variations/{$stock->id_stock}/stock");

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.id_stock', $stock->id_stock);
    }

    public function test_should_list_variation_stock_entries_with_product_variation_id(): void
    {
        $stockEntries = ProductStockEntries::factory()->create();

        $response = $this->get("/api/variations/{$stockEntries->product_variation_id}/entries/stock");

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.product_variation_id', $stockEntries->product_variation_id);
    }

    #[DataProvider('notFoundProvider')]
    public function test_should_return_404_if_resource_not_found(string $url, string $expectedMessage): void
    {
        $response = $this->get($url);

        $response
            ->assertStatus(404)
            ->assertJson(['message' => $expectedMessage]);
    }
}
