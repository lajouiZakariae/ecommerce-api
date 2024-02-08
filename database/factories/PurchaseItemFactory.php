<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PurchaseItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\PurchaseItem>
 */
final class PurchaseItemFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = PurchaseItem::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'purchase_id' => \App\Models\Purchase::factory(),
            'product_id' => \App\Models\Product::factory(),
            'quantity' => fake()->randomNumber(),
        ];
    }
}
