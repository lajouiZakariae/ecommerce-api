<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            "product_id"        => fake()->numberBetween(1, 20),
            "store_id"          => fake()->numberBetween(1, 5),
            "quantity"          => fake()->randomNumber(),
            "min_stock_level"   => 10,
            "max_stock_level"   => 500,
        ];
    }
}
