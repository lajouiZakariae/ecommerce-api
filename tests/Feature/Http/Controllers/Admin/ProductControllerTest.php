<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ProductController
 */
final class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // #[Test]
    // public function index_behaves_as_expected(): void
    // {
    //     $products = Product::factory()->count(3)->create();

    //     $response = $this->get(route('products.index'));

    //     $response->assertOk();

    //     $response->assertJsonStructure(['data' => [
    //         '*' => [
    //             'id', 'title', 'description', 'cost', 'price', 'stock_quantity', 'published', 'category_id', 'store_id', 'url'
    //         ]
    //     ]]);
    // }

    #[Test]
    public function store_saves(): void
    {
        $category = Category::factory()->create();

        $title = $this->faker->sentence(4);

        $response = $this->postJson(route('products.store'), [
            'title' => $title,
            'category_id' => $category->id
        ]);

        $products = Product::query()
            ->where('title', $title)
            ->where('category_id', $category->id)
            ->get();

        $this->assertCount(1, $products);

        $response->assertCreated();
    }

    #[Test]
    public function show_behaves_as_expected(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertJsonStructure([
            'id', 'title', 'description', 'cost', 'price', 'stock_quantity', 'published', 'category_id', 'store_id', 'url'
        ]);
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $product = Product::factory()->create();
        $title = $this->faker->sentence(4);
        $cost = $this->faker->randomFloat(max: 25000);
        $price = $this->faker->randomFloat(max: 2500);
        $stock_quantity = $this->faker->numberBetween(0, 1000);
        $published = $this->faker->boolean;

        $category = Category::factory()->create();

        $response = $this->put(route('products.update', $product), [
            'title' => $title,
            'cost' => $cost,
            'price' => $price,
            'stock_quantity' => $stock_quantity,
            'published' => $published,
            'category_id' => $category->id,
        ]);

        $product->refresh();

        $response->assertNoContent();

        $this->assertEquals($title, $product->title);
        // $this->assertEquals($cost, $product->cost);
        // $this->assertEquals($price, $product->price);
        $this->assertEquals($stock_quantity, $product->stock_quantity);
        $this->assertEquals($published, $product->published);
        $this->assertEquals($category->id, $product->category_id);
    }

    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product));

        $response->assertNoContent();

        $this->assertModelMissing($product);
    }
}
