<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Order>
 */
final class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name,
            'email' => fake()->safeEmail,
            'phone_number' => fake()->phoneNumber,
            'status' => fake()->randomElement(['pending', 'in transit', 'delivered', 'delivery attempt', 'cancelled', 'return to sender']),
            'city' => fake()->city,
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
            'zip_code' => fake()->word,
            'coupon_code_id' => \App\Models\CouponCode::factory(),
            'address' => fake()->address,
            'delivery' => fake()->boolean,
        ];
    }
}
