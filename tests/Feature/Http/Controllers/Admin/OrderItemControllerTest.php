<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\OrderItemController
 */
final class OrderItemControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $order = Order::factory()->create();

        for ($i = 0; $i < 3; $i++) {
            $order->orderItems()->save(
                new OrderItem([
                    'product_id' => Product::factory()->create()->id,
                    'quantity' => fake()->numberBetween(0, 999999),
                ])
            );
        }

        $response = $this->get(route('order-items.index', ['order' => $order->id]));

        $response
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has(3);
            })
            ->assertJsonStructure(['*' => ['id', 'order_id', 'product_id', 'quantity']]);
    }

    #[Test]
    public function store_saves(): void
    {
        $order = Order::factory()->create();

        $product_id = Product::factory()->create()->id;
        $quantity = $this->faker->numberBetween(0, 10000);

        $response = $this->post(route('order-items.store', ['order' => $order->id]), [
            'product_id' => $product_id,
            'quantity' => $quantity,
        ]);

        $orderItems = OrderItem::query()
            ->where('order_id', $order->id)
            ->where('product_id', $product_id)
            ->where('quantity', $quantity)
            ->get();

        $this->assertCount(1, $orderItems);
        $orderItem = $orderItems->first();

        $response->assertCreated();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $orderItem = OrderItem::factory()->create();

        $response = $this->get(route('order-items.show', [
            'order' => $orderItem->order->id,
            'order_item' => $orderItem->id
        ]));

        $response
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($orderItem) {
                $json->where('order_id', $orderItem->order->id)->etc();
            });
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $orderItem = OrderItem::factory()->create();
        $product_id = Product::factory()->create()->id;
        $quantity = $this->faker->numberBetween(0, 10000);

        $response = $this->put(route(
            'order-items.update',
            [
                'order' => $orderItem->order->id,
                'order_item' => $orderItem->id
            ]
        ), [
            'product_id' => $product_id,
            'quantity' => $quantity,
        ]);

        $orderItem->refresh();

        $response->assertNoContent();

        $this->assertEquals($orderItem->order->id, $orderItem->order_id);

        $this->assertEquals($product_id, $orderItem->product_id);

        $this->assertEquals($quantity, $orderItem->quantity);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $orderItem = OrderItem::factory()->create();

        $response = $this->delete(route('order-items.destroy', [
            'order' => $orderItem->order->id,
            'order_item' => $orderItem->id
        ]));

        $response->assertNoContent();

        $this->assertModelMissing($orderItem);
    }

    #[Test]
    public function increment_quantity_is_working(): void
    {
        $orderItem = OrderItem::factory()->create();

        $orderItemQuantity = $orderItem->quantity;

        $response = $this->patch(route('order-items.increment-quantity', [
            'order' => $orderItem->order->id,
            'order_item' => $orderItem->id
        ]));

        $response->assertNoContent();

        $orderItem->refresh();

        $this->assertEquals($orderItem->quantity, $orderItemQuantity + 1);
    }

    #[Test]
    public function decrement_quantity_is_working(): void
    {
        $orderItem = OrderItem::factory()->create();

        $orderItemQuantity = $orderItem->quantity;

        $response = $this->patch(route('order-items.decrement-quantity', [
            'order' => $orderItem->order->id,
            'order_item' => $orderItem->id
        ]));

        $response->assertNoContent();

        $orderItem->refresh();

        $this->assertEquals($orderItem->quantity, $orderItemQuantity - 1);
    }
}
