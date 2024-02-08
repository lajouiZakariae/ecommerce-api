<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStoreRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;

/**
 * @group Orders
 */
#[ApiResource('orders')]
class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $orders = Order::query()->with([
            'orderItems' => [
                'product:id,title,price'
            ]
        ])->get(['id', 'full_name', 'status', 'delivery', 'created_at']);

        $orders = $orders->map(function (Order $order) {
            $order['order_items_url'] = route('order-items.index', ['order' => $order->id]);
            $order['order_items_count'] = $order->orderItems->count();

            $order->orderItems->each(function (OrderItem $orderItem) use ($order) {
                $orderItem->url = route('order-items.show', ['order' => $order->id, 'order_item' => $orderItem->id]);
                $orderItem->product->url = route('products.show', ['product' => $orderItem->product->id]);
                $orderItem->total_price = round($orderItem->quantity * $orderItem->product->price, 2);
            });

            $total_price =
                $order->orderItems->reduce(function ($acc, OrderItem $orderItem) {
                    return $acc + ($orderItem->quantity * $orderItem->product->price);
                }, 0);

            $order->total_price = round($total_price, 2);

            return $order;
        });

        return response($orders);
    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \App\Http\Requests\Admin\OrderStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderStoreRequest $request): Response
    {
        $data = $request->validated();

        $order = Order::create($data);

        return response($order, Response::HTTP_CREATED);
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order): Response
    {
        $orderData = $order->load([
            'paymentMethod'
        ]);

        return response(new OrderResource($orderData));
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \App\Http\Requests\Admin\OrderUpdateRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderUpdateRequest $request, Order $order): Response
    {
        $data = $request->validated();

        $order->update($data);

        return response()->noContent();
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order): Response
    {
        $order->delete();

        return response()->noContent();
    }
}
