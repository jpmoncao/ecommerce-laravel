<?php

namespace Tests\Feature;

use App\Models\CartItems;
use App\Models\ProductStockEntries;
use App\Models\ProductStocks;
use App\Models\ProductVariations;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartItemsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_should_add_item_in_cart(): void
    {
        $user = User::factory()->create();
        $productStock = ProductStocks::factory()->create();

        $cartItemData = [
            'product_variation_id' => $productStock->id_stock,
            'quantity' => $productStock->quantity
        ];

        $this->actingAs($user);

        $response = $this->post('/api/cart-items', $cartItemData);

        $this->assertDatabaseHas('cart_items', [
            "cart_id" => $user->id_user,
            "product_variation_id" => $productStock->id_stock,
            "quantity" => $productStock->quantity
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(['message' => 'Product added to cart successfully!'])
            ->assertJsonPath('data.items.0.product_variation_id', $productStock->id_stock)
            ->assertJsonCount(1, 'data.items');
    }

    public function test_should_remove_item_of_cart(): void
    {
        $user = User::factory()->create();
        $cartItem = CartItems::factory()->create(['cart_id' => $user->id_user]);

        $this->actingAs($user);

        $response = $this->delete("/api/cart-items/$cartItem->id_cart_item");

        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $user->id_user,
            'id_cart_item' => $cartItem->id_cart_item
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Product removed from the cart successfully!']);
    }
}
