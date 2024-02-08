<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\ImageController
 */
final class ImageControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $images = Image::factory()->count(3)->create();

        $response = $this->get(route('images.index'));

        $response->assertOk()
            ->assertJsonStructure(['*' => ['id', 'alt_text', 'url', 'product_id']])
            ->assertJson(function (AssertableJson $json) {
                $json->has(3);
            });
    }

    #[Test]
    public function store_saves(): void
    {
        $alt_text = $this->faker->word;
        $product_id = Product::factory()->create()->id;

        Storage::fake('public');

        $image_file = UploadedFile::fake()->image('lorem.jpg');

        $response = $this->post(route('images.store'), [
            'alt_text' => $alt_text,
            'product_id' => $product_id,
            'image' => $image_file,
        ]);

        $images = Image::query()
            ->where('alt_text', $alt_text)
            ->where('product_id', $product_id)
            ->get();

        $this->assertCount(1, $images);

        $image = $images->first();

        $response->assertCreated();

        Storage::disk('public')->assertExists('products/' . $image_file->hashName());
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $image = Image::factory()->create();

        $response = $this->get(route('images.show', $image));

        $response->assertOk();
        $response->assertJsonStructure(['id', 'alt_text', 'url', 'product_id']);

        $response->assertJson(function (AssertableJson $json) use ($image) {
            $json
                ->where('alt_text', $image->alt_text)
                ->where('product_id', $image->product_id)
                ->where('url', Storage::disk('public')->url($image->path))
                ->etc();
        });
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $image = Image::factory()->create();

        $alt_text = $this->faker->word;

        $product_id = Product::factory()->create()->id;

        Storage::fake('public');

        $image_to_upload = UploadedFile::fake()->image('image_update.jpeg');

        $response = $this->put(route('images.update', $image), [
            'alt_text' => $alt_text,
            'product_id' => $product_id,
            'image' => $image_to_upload,
        ]);

        Storage::disk('public')->assertMissing($image->path); // old image gone

        $image->refresh();

        $response->assertNoContent();

        $this->assertEquals($alt_text, $image->alt_text);
        $this->assertEquals($product_id, $image->product_id);

        Storage::disk('public')->assertExists('products/' . $image_to_upload->hashName());
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {

        $image = Image::factory()->create();

        Storage::disk('public')->assertExists($image->path);

        $response = $this->delete(route('images.destroy', $image));

        $response->assertNoContent();

        $this->assertModelMissing($image);

        Storage::disk('public')->assertMissing($image->path);
    }
}
