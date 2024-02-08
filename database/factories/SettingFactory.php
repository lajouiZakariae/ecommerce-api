<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Platform;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Setting>
 */
final class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'platform' => fake()->randomElement(array_column(Platform::cases(), 'value')),
            'settings_value' => [
                'theme' => fake()->randomElement(['green', 'blue', 'red']),
                'font' => fake()->randomElement(['consolas', 'poppins']),
                'maintenanceMode' => fake()->boolean(1)
            ],
            'settings_default' => [
                'theme' => fake()->randomElement(['green', 'blue', 'red']),
                'font' => fake()->randomElement(['consolas', 'poppins']),
                'maintenanceMode' => fake()->boolean(1)
            ],
        ];
    }
}
