<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Product;
use File;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productsData = json_decode(File::get(base_path('json/products.json')));

        $productsData = collect($productsData)->map(function ($product) {
            $product->created_at = fake()->time();
            return $product;
        });

        foreach ($productsData as $product) {
            $product = Product::create((array) $product);

            if (in_array($product->id, [1, 2])) continue;

            Inventory::insert([
                'product_id' => $product->id,
                'quantity'  => fake()->numberBetween(0, 100),
                'min_stock_level' => 10,
                'max_stock_level' => 200,
            ]);

            Inventory::insert([
                'product_id' => $product->id,
                'quantity'  => fake()->numberBetween(0, 100),
                'min_stock_level' => 10,
                'max_stock_level' => 200,
            ]);
        }
    }
}
