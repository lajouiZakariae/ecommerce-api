<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Purchase>
 */
final class PurchaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Purchase::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'supplier_id' => \App\Models\Supplier::factory(),
            'delivery_date' => fake()->date(),
            'paid' => fake()->boolean,
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
            'store_id' => \App\Models\Store::factory(),
        ];
    }
}
