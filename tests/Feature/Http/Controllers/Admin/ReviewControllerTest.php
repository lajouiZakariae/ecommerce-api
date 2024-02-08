<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ReviewController
 */
final class ReviewControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $reviews = Review::factory()->count(3)->create();

        $response = $this->get(route('reviews.index'));

        $response->assertOk();
        $response->assertJsonStructure(['*' => ['id', 'email', 'body', 'approved', 'product_id']]);
        $response->assertJson(function (AssertableJson $json) {
            $json->has(3);
        });
    }

    #[Test]
    public function store_saves(): void
    {
        $email = $this->faker->safeEmail;
        $body = $this->faker->text;
        $product_id = Product::factory()->create()->id;
        $approved = $this->faker->boolean;

        $response = $this->post(route('reviews.store'), [
            'email' => $email,
            'body' => $body,
            'product_id' => $product_id,
            'approved' => $approved,
        ]);

        $reviews = Review::query()
            ->where('email', $email)
            ->where('body', $body)
            ->where('product_id', $product_id)
            ->where('approved', $approved)
            ->get();

        $this->assertCount(1, $reviews);
        $review = $reviews->first();

        $response->assertCreated();
    }

    #[Test]
    public function show_behaves_as_expected(): void
    {
        $review = Review::factory()->create();

        $response = $this->get(route('reviews.show', $review));

        $response->assertOk();
        $response->assertJsonStructure(['id', 'email', 'body', 'approved', 'product_id']);

        $response->assertJson(function (AssertableJson $json) use ($review) {
            $json->where('email', $review->email)->etc();
        });
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $review = Review::factory()->create();
        $email = $this->faker->safeEmail;
        $body = $this->faker->text;
        $product_id = Product::factory()->create()->id;
        $approved = $this->faker->boolean;

        $response = $this->put(route('reviews.update', $review), [
            'email' => $email,
            'body' => $body,
            'product_id' => $product_id,
            'approved' => $approved,
        ]);

        $review->refresh();

        $response->assertNoContent();

        $this->assertEquals($email, $review->email);
        $this->assertEquals($body, $review->body);
        $this->assertEquals($product_id, $review->product_id);
        $this->assertEquals($approved, $review->approved);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $review = Review::factory()->create();

        $response = $this->delete(route('reviews.destroy', $review));

        $response->assertNoContent();

        $this->assertModelMissing($review);
    }
}
