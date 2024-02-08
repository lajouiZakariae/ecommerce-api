<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\StoreController
 */
final class StoreControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        Store::factory()->count(3)->create();

        $response = $this->get(route('stores.index'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                '*' => ['id', 'name', 'address', 'url']
            ])
            ->assertJson(function (AssertableJson $json) {
                $json->has(3);
            });
    }

    #[Test]
    public function store_saves(): void
    {
        $name = $this->faker->name;
        $address = $this->faker->word;

        $response = $this->post(route('stores.store'), [
            'name' => $name,
            'address' => $address,
        ]);

        $stores = Store::query()
            ->where('name', $name)
            ->where('address', $address)
            ->get();

        $this->assertCount(1, $stores);

        $store = $stores->first();

        $response->assertCreated();
        $response->assertJson(function (AssertableJson $json) use ($store) {
            $json
                ->where('name', $store->name)
                ->where('address', $store->address)
                ->etc();
        });
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $store = Store::factory()->create();
        $name = $this->faker->name;
        $address = $this->faker->word;

        $response = $this->put(route('stores.update', $store), [
            'name' => $name,
            'address' => $address,
        ]);

        $store->refresh(); // Get Updated Data

        $response->assertNoContent();

        $this->assertEquals($name, $store->name);
        $this->assertEquals($address, $store->address);
    }

    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $store = Store::factory()->create();

        $response = $this->delete(route('stores.destroy', $store));

        $response->assertNoContent();

        $this->assertModelMissing($store);
    }
}
