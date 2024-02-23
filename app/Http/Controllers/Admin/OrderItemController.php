<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItemStoreRequest;
use App\Http\Resources\Admin\OrderItemResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\ScopeBindings;

class OrderItemController extends Controller
{
    /**
     * Display a listing of order items for a specific order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    // public function index(Order $order): Response
    // {
    //     $orderItems = $order
    //         ->orderItems()
    //         ->with([
    //             'product:id,title,price' => ['thumbnail']
    //         ])
    //         ->get();

    //     $order->orderItems->each(
    //         function (OrderItem $orderItem) {
    //             $orderItem->product->thumbnail->url = $orderItem->product->thumbnail->imageUrl();
    //         }
    //     );

    //     return response(OrderItemResource::collection($orderItems));
    // }

    /**
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function store(OrderItemStoreRequest $request)
    {
    }

    /**
     * Display the specified order item.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    // public function show(Order $order, OrderItem $orderItem): Response
    // {
    //     return response(new OrderItemResource($orderItem));
    // }

    /**
     * Update the specified order item in storage for a specific order.
     *
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    // public function update($order, OrderItem $orderItem): Response
    // {
    //     $data = request()->validate([
    //         'product_id' => ['required', 'exists:products,id'],
    //         'quantity' => ['required', 'integer'],
    //     ]);

    //     $orderItem->update($data);

    //     return response()->noContent();
    // }

    /**
     * Remove the specified order item from storage for a specific order.
     *
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    // public function destroy($order, OrderItem $orderItem): Response
    // {
    //     $orderItem->delete();

    //     return response()->noContent();
    // }

    /**
     * Increment the quantity of the specified order item for a specific order.
     *
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    // public function incrementQuantity($orderId, OrderItem $orderItem): Response
    // {
    //     if ($orderItem->quantity === 18_446_744_073_709_551_615) {
    //         return response(['error' => 'maximum value reached'], Response::HTTP_BAD_REQUEST);
    //     }

    //     $orderItem->increment('quantity');

    //     return response()->noContent();
    // }

    /**
     * Decrement the quantity of the specified order item for a specific order.
     *
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    // public function decrementQuantity($orderId, OrderItem $orderItem): Response
    // {
    //     if ($orderItem->quantity === 0) {
    //         return response(['error' => 'minimum value reached'], Response::HTTP_BAD_REQUEST);
    //     }

    //     $orderItem->decrement('quantity');

    //     return response()->noContent();
    // }
}
