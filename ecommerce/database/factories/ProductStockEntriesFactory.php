<?php

namespace Database\Factories;

use App\Models\ProductVariations;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductStockEntries>
 */
class ProductStockEntriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_variation_id' => ProductVariations::factory(),
            'quantity' => $this->faker->randomDigitNotNull()
        ];
    }
}
