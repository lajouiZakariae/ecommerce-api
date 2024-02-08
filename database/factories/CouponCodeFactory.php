<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CouponCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CouponCode>
 */
final class CouponCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CouponCode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'code' => fake()->word,
            'amount' => fake()->randomNumber(),
        ];
    }
}
