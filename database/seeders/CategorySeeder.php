<?php

namespace Database\Seeders;

use App\Models\Category;
use File;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = collect(json_decode(File::get(base_path('json/categories.json'))));

        $categories->each(function (object $category) {
            $category->slug = str($category->name)->slug();
            Category::create((array) $category);
        });
    }
}
