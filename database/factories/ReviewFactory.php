<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Review>
 */
final class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'body' => fake()->text,
            'client_id' => fake()->numberBetween(1, 3),
            'product_id' => fake()->numberBetween(1, 20),
            'approved' => fake()->boolean,
        ];
    }
}
