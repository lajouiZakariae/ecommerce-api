<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Media>
 */
final class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'alt_text' => fake()->word,
            'path' => 'products/' . fake()->image(storage_path("app/public/products"), fullPath: false),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
