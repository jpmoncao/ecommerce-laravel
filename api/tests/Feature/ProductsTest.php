<?php

namespace Tests\Feature;

use App\Models\Products;
use App\Models\ProductVariations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private const VALID_PRODUCT_ID = 1;
    private const INVALID_PRODUCT_ID = 999;

    public static function notFoundProvider(): array
    {
        return [
            'product not found' => ['/api/products/' . self::INVALID_PRODUCT_ID, 'Product not found!'],
            'product variations not found' => ['/api/products/' . self::INVALID_PRODUCT_ID . '/variations', 'Product not found!'],
        ];
    }

    public function test_should_list_all_products(): void
    {
        Products::factory()->count(3)->create();

        $response = $this->get('/api/products');

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Products listed successfully!'])
            ->assertJsonCount(3, 'data');
    }

    public function test_should_list_product_by_id(): void
    {
        Products::factory()->create(['id_product' => self::VALID_PRODUCT_ID]);

        $response = $this->get("/api/products/" . self::VALID_PRODUCT_ID);

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Product listed successfully!'])
            ->assertJsonPath('data.id_product', self::VALID_PRODUCT_ID);
    }

    public function test_should_list_product_variations_by_product_id(): void
    {
        Products::factory()->create(['id_product' => self::VALID_PRODUCT_ID]);
        ProductVariations::factory()->create(['product_id' => self::VALID_PRODUCT_ID]);

        $response = $this->get("/api/products/" . self::VALID_PRODUCT_ID . "/variations");

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Product with variations listed successfully!'])
            ->assertJsonCount(1, 'data.variants')
            ->assertJsonPath('data.variants.0.product_id', self::VALID_PRODUCT_ID);
    }

    public function test_should_list_all_products_with_variations_and_stock(): void
    {
        Products::factory()->create(['id_product' => self::VALID_PRODUCT_ID]);
        ProductVariations::factory()->create(['product_id' => self::VALID_PRODUCT_ID]);

        $response = $this->get("/api/products/variations");

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Products with variations listed successfully!'])
            ->assertJsonCount(1, 'data.0.variants')
            ->assertJsonPath('data.0.variants.0.product_id', self::VALID_PRODUCT_ID);
    }

    public function test_should_create_product(): void
    {
        $PRODUCT_NAME = $this->faker->word();

        $response = $this->post("/api/products", ['name' => $PRODUCT_NAME]);

        $this->assertDatabaseHas('products', ['name' => $PRODUCT_NAME]);

        $response
            ->assertStatus(201)
            ->assertJson(['message' => 'Products created successfully!'])
            ->assertJsonPath('data.name', $PRODUCT_NAME);
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
