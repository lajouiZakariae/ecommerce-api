<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\PaymentMethodController
 */
final class PaymentMethodControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        PaymentMethod::factory()->count(3)->create();

        $response = $this->get(route('payment-methods.index'));

        $response->assertOk();

        $response->assertJsonStructure(['*' => ['id', 'name', 'description', 'url']]);
    }

    #[Test]
    public function store_saves(): void
    {
        $name = $this->faker->name;

        $response = $this->post(route('payment-methods.store'), [
            'name' => $name,
        ]);

        $paymentMethods = PaymentMethod::query()
            ->where('name', $name)
            ->get();

        $this->assertCount(1, $paymentMethods);

        $paymentMethod = $paymentMethods->first();

        $response->assertCreated();
    }

    #[Test]
    public function store_respond_with_errors(): void
    {
        $name = '';
        $name_with_special_chars = '';

        $response = $this->post(route('payment-methods.store'), [
            'name' => $name,
        ]);

        $response->assertInvalid('name');

        $response = $this->post(route('payment-methods.store'), [
            'name' => $name_with_special_chars,
        ]);

        $response->assertInvalid('name');
    }

    #[Test]
    public function show_behaves_as_expected(): void
    {
        $paymentMethod = PaymentMethod::factory()->create();

        $response = $this->get(route('payment-methods.show', $paymentMethod));

        $response->assertOk();

        $response->assertJsonStructure(['id', 'name', 'description', 'url']);
        $response->assertJson(function (AssertableJson $json) use ($paymentMethod) {
            $json
                ->where('id', $paymentMethod->id)
                ->where('name', $paymentMethod->name)
                ->where('description', $paymentMethod->description)
                ->where('url', route("payment-methods.show", $paymentMethod));
        });
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $paymentMethod = PaymentMethod::factory()->create();

        $name = $this->faker->name;

        $response = $this->put(route('payment-methods.update', $paymentMethod), [
            'name' => $name,
        ]);

        $paymentMethod->refresh();

        $response->assertNoContent();
        // $response->assertJsonStructure([]);

        $this->assertEquals($name, $paymentMethod->name);
    }

    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $paymentMethod = PaymentMethod::factory()->create();

        $response = $this->delete(route('payment-methods.destroy', $paymentMethod));

        $response->assertNoContent();

        $this->assertModelMissing($paymentMethod);
    }

    #[Test]
    public function destroy_responds_with_not_found(): void
    {
        $response = $this->delete(route('payment-methods.destroy', ['payment_method' => 188]));

        $response->assertNotFound();
    }
}
