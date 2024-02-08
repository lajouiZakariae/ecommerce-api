<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\PurchaseItemController
 */
final class PurchaseItemControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $purchase = Purchase::factory()->create();

        for ($i = 0; $i < 3; $i++) {
            $purchase->purchaseItems()->save(
                new PurchaseItem([
                    'product_id' => Product::factory()->create()->id,
                    'quantity' => fake()->numberBetween(0, 999999),
                ])
            );
        }

        $response = $this->get(route('purchase-items.index', ['purchase' => $purchase->id]));

        $response
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has(3);
            })
            ->assertJsonStructure(['*' => ['id', 'purchase_id', 'product_id', 'quantity']]);
    }

    #[Test]
    public function store_saves(): void
    {
        $purchase = Purchase::factory()->create();

        $product_id = Product::factory()->create()->id;
        $quantity = $this->faker->numberBetween(0, 10000);

        $response = $this->post(route('purchase-items.store', ['purchase' => $purchase->id]), [
            'product_id' => $product_id,
            'quantity' => $quantity,
        ]);

        $purchaseItems = PurchaseItem::query()
            ->where('purchase_id', $purchase->id)
            ->where('product_id', $product_id)
            ->where('quantity', $quantity)
            ->get();
        $this->assertCount(1, $purchaseItems);
        $purchaseItem = $purchaseItems->first();

        $response->assertCreated();
    }

    #[Test]
    public function show_behaves_as_expected(): void
    {
        $purchaseItem = PurchaseItem::factory()->create();

        $response = $this->get(route('purchase-items.show', [
            'purchase' => $purchaseItem->purchase->id,
            'purchase_item' => $purchaseItem->id
        ]));

        $response
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($purchaseItem) {
                $json->where('purchase_id', $purchaseItem->purchase->id)->etc();
            });
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $purchaseItem = PurchaseItem::factory()->create();
        $product_id = Product::factory()->create()->id;
        $quantity = $this->faker->numberBetween(0, 10000);

        $response = $this->put(route(
            'purchase-items.update',
            [
                'purchase' => $purchaseItem->purchase->id,
                'purchase_item' => $purchaseItem->id
            ]
        ), [
            'product_id' => $product_id,
            'quantity' => $quantity,
        ]);

        $purchaseItem->refresh();

        $response->assertNoContent();

        $this->assertEquals($purchaseItem->purchase->id, $purchaseItem->purchase_id);

        $this->assertEquals($product_id, $purchaseItem->product_id);

        $this->assertEquals($quantity, $purchaseItem->quantity);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $purchaseItem = PurchaseItem::factory()->create();

        $response = $this->delete(route('purchase-items.destroy', [
            'purchase' => $purchaseItem->purchase->id,
            'purchase_item' => $purchaseItem->id,
        ]));

        $response->assertNoContent();

        $this->assertModelMissing($purchaseItem);
    }

    #[Test]
    public function increment_quantity_is_working(): void
    {
        $purchaseItem = PurchaseItem::factory()->create();

        $purchaseItemQuantity = $purchaseItem->quantity;

        $response = $this->patch(route('purchase-items.increment-quantity', [
            'purchase' => $purchaseItem->purchase->id,
            'purchase_item' => $purchaseItem->id
        ]));

        $response->assertNoContent();

        $purchaseItem->refresh();

        $this->assertEquals($purchaseItem->quantity, $purchaseItemQuantity + 1);
    }

    #[Test]
    public function decrement_quantity_is_working(): void
    {
        $purchaseItem = PurchaseItem::factory()->create();

        $purchaseItemQuantity = $purchaseItem->quantity;

        $response = $this->patch(route('purchase-items.decrement-quantity', [
            'purchase' => $purchaseItem->purchase->id,
            'purchase_item' => $purchaseItem->id
        ]));

        $response->assertNoContent();

        $purchaseItem->refresh();

        $this->assertEquals($purchaseItem->quantity, $purchaseItemQuantity - 1);
    }
}
