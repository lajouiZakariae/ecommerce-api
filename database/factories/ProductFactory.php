<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Product>
 */
final class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->optional()->text,
            'cost' => fake()->randomFloat(max: 2500),
            'price' => fake()->randomFloat(max: 2500),
            'stock_quantity' => fake()->randomNumber(),
            'published' => fake()->boolean,
            'category_id' => Category::factory(),
            'store_id' => Store::factory(),
        ];
    }
}
