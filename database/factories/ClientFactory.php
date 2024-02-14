<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'phone_number' => fake()->phoneNumber(),
            'city' => fake()->city(),
            'zip_code' => fake()->numberBetween(70000, 90000),
            'address' => fake()->address(),
            // 'delivery' => fake()->boolean(),
        ];
    }
}
