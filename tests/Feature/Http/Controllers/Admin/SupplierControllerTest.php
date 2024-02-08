<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\SupplierController
 */
final class SupplierControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $suppliers = Supplier::factory()->count(3)->create();

        $response = $this->get(route('suppliers.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'email', 'phone_number', 'address']
        ])->assertJson(function (AssertableJson $json) {
            $json->has(3);
        });
    }

    #[Test]
    public function store_saves(): void
    {
        $name = $this->faker->name;
        $email = $this->faker->safeEmail;
        $phone_number = $this->faker->phoneNumber;
        $address = $this->faker->word;

        $response = $this->post(route('suppliers.store'), [
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number,
            'address' => $address,
        ]);

        $suppliers = Supplier::query()
            ->where('name', $name)
            ->where('email', $email)
            ->where('phone_number', $phone_number)
            ->where('address', $address)
            ->get();

        $this->assertCount(1, $suppliers);

        $supplier = $suppliers->first();

        $response->assertCreated();
    }

    #[Test]
    public function show_behaves_as_expected(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->get(route('suppliers.show', $supplier));

        $response->assertOk();
        $response->assertJsonStructure(['id', 'name', 'email', 'phone_number', 'address']);
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $supplier = Supplier::factory()->create();
        $name = $this->faker->name;
        $email = $this->faker->safeEmail;
        $phone_number = $this->faker->phoneNumber;
        $address = $this->faker->word;

        $response = $this->put(route('suppliers.update', $supplier), [
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number,
            'address' => $address,
        ]);

        $supplier->refresh();

        $response->assertNoContent();

        $this->assertEquals($name, $supplier->name);
        $this->assertEquals($email, $supplier->email);
        $this->assertEquals($phone_number, $supplier->phone_number);
        $this->assertEquals($address, $supplier->address);
    }

    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->delete(route('suppliers.destroy', $supplier));

        $response->assertNoContent();

        $this->assertModelMissing($supplier);
    }
}
