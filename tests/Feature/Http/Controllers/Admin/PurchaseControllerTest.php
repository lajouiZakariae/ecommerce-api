<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Purchase;
use App\Models\PaymentMethod;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\PurchaseController
 */
final class PurchaseControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        Purchase::factory()->count(3)->create();

        $response = $this->get(route('purchases.index'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'supplier' => ['id', 'name'],
                    'delivery_date',
                    'payment_method' => ['id', 'name'],
                    'paid',
                    'purchase_items_count',
                ]
            ])
            ->assertJson(function (AssertableJson $json) {
                $json->has(3);
            });
    }

    #[Test]
    public function store_saves(): void
    {
        $paid = fake()->boolean;
        $delivery_date = fake()->date();
        $supplier_id = Supplier::factory()->create()->id;
        $store_id = Store::factory()->create()->id;
        $payment_method_id = PaymentMethod::factory()->create()->id;

        $response = $this->post(route('purchases.store'), [
            'delivery_date' => $delivery_date,
            'paid' => $paid,
            'supplier_id' => $supplier_id,
            'store_id' => $store_id,
            'payment_method_id' => $payment_method_id,
        ]);

        $purchases = Purchase::query()
            ->where('delivery_date', $delivery_date)
            ->where('paid', $paid)
            ->where('supplier_id', $supplier_id)
            ->where('store_id', $store_id)
            ->where('payment_method_id', $payment_method_id)
            ->get();

        $this->assertCount(1, $purchases);

        $purchase = $purchases->first();

        $response->assertCreated();
    }

    #[Test]
    public function show_behaves_as_expected(): void
    {
        $purchase = Purchase::factory()->create();

        $response = $this->get(route('purchases.show', $purchase));

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'paid',
            'delivery_date',
            'supplier' => ['id', 'name', 'url'],
            'payment_method' => ['id', 'name', 'url'],
            'store' => ['id', 'name'],
        ]);
    }


    #[Test]
    public function update_behaves_as_expected(): void
    {
        $purchase = Purchase::factory()->create();

        $paid = fake()->boolean;
        $delivery_date = fake()->date();
        $supplier_id = Supplier::factory()->create()->id;
        $store_id = Store::factory()->create()->id;
        $payment_method_id = PaymentMethod::factory()->create()->id;

        $response = $this->put(route('purchases.update', $purchase), [
            'delivery_date' => $delivery_date,
            'paid' => $paid,
            'supplier_id' => $supplier_id,
            'store_id' => $store_id,
            'payment_method_id' => $payment_method_id,
        ]);

        $purchase->refresh();

        $response->assertNoContent();

        $this->assertEquals($paid, $purchase->paid);
        $this->assertEquals($delivery_date, $purchase->delivery_date->toDateString());
        $this->assertEquals($supplier_id, $purchase->supplier_id);
        $this->assertEquals($store_id, $purchase->store_id);
        $this->assertEquals($payment_method_id, $purchase->payment_method_id);
    }

    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $purchase = Purchase::factory()->create();

        $response = $this->delete(route('purchases.destroy', $purchase));

        $response->assertNoContent();

        $this->assertModelMissing($purchase);
    }
}
