<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            Image::insert([
                'alt_text' => fake()->word,
                'path' => 'products/placeholder.jpg',
                'product_id' => $i,
            ]);
        }
    }
}
