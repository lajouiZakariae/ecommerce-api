<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderItemResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\ScopeBindings;

/**
 * @group Order Items
 * @authenticated
 */
#[Prefix('orders/{order}')]
#[ApiResource('order-items', except: 'show')]
class OrderItemController extends Controller
{
    /**
     * Display a listing of order items for a specific order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    #[Get('order-items', name: 'order-items.index')]
    #[ScopeBindings]
    public function index(Order $order): Response
    {
        $orderItems = $order->orderItems;

        return response(OrderItemResource::collection($orderItems));
    }

    /**
     * Store a newly created order item in storage for a specific order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function store(Order $order): Response
    {
        $data = request()->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer'],
        ]);

        $orderItem = new OrderItem($data);

        $order->orderItems()->save($orderItem);

        return response('', Response::HTTP_CREATED);
    }

    /**
     * Display the specified order item.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    #[Get('order-items/{order_item}', name: 'order-items.show')]
    #[ScopeBindings]
    public function show(Order $order, OrderItem $orderItem): Response
    {
        return response(new OrderItemResource($orderItem));
    }

    /**
     * Update the specified order item in storage for a specific order.
     *
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    public function update($order, OrderItem $orderItem): Response
    {
        $data = request()->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer'],
        ]);

        $orderItem->update($data);

        return response()->noContent();
    }

    /**
     * Remove the specified order item from storage for a specific order.
     *
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($order, OrderItem $orderItem): Response
    {
        $orderItem->delete();

        return response()->noContent();
    }

    /**
     * Increment the quantity of the specified order item for a specific order.
     *
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    #[Patch('order-items/{order_item}/increment-quantity', 'order-items.increment-quantity')]
    public function incrementQuantity($orderId, OrderItem $orderItem): Response
    {
        if ($orderItem->quantity === 18_446_744_073_709_551_615) {
            return response(['error' => 'maximum value reached'], Response::HTTP_BAD_REQUEST);
        }

        $orderItem->query()->increment('quantity');

        return response()->noContent();
    }

    /**
     * Decrement the quantity of the specified order item for a specific order.
     *
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    #[Patch('order-items/{order_item}/decrement-quantity', 'order-items.decrement-quantity')]
    public function decrementQuantity($orderId, OrderItem $orderItem): Response
    {
        if ($orderItem->quantity === 0) {
            return response(['error' => 'minimum value reached'], Response::HTTP_BAD_REQUEST);
        }

        $orderItem->query()->decrement('quantity');

        return response()->noContent();
    }
}
