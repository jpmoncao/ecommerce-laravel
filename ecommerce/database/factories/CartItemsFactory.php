<?php

namespace Database\Factories;

use App\Models\ProductVariations;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItems>
 */
class CartItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_id' => User::factory(),
            'product_variation_id' => ProductVariations::factory(),
            'quantity' => $this->faker->randomDigitNotNull()
        ];
    }
}
