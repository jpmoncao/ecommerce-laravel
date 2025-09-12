<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductVariations;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductStocks>
 */
class ProductStocksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_stock' => ProductVariations::factory(),
            'quantity' => $this->faker->randomDigitNotNull(),
        ];
    }
}
