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
    private function calculateTotals(Order $order): Order
    {
        $order->orderItems->each(function (OrderItem $orderItem) {
            $orderItem->setTotalPrice();
        });

        $order->setTotalPrice();
        return $order;
    }

    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $orders = Order::query()
            ->with([
                'orderItems' => [
                    'product:id,title,price'
                ],
            ])
            ->select(['id', 'full_name', 'status', 'delivery', 'created_at'])
            ->get();

        $orders = $orders->map(fn (Order $order) => $this->calculateTotals($order));

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
        $order = $order->load([
            'paymentMethod',
            'orderItems' => ['product:id,title,price']
        ]);

        $order = $this->calculateTotals($order);

        $order->orderItems->each(function (OrderItem $orderItem) {
            $orderItem->url = route(
                'order-items.show',
                ['order' => $orderItem->order_id, 'order_item' => $orderItem->id]
            );

            $orderItem->increment_quantity_url =  route(
                'order-items.increment-quantity',
                ['order' => $orderItem->order_id, 'order_item' => $orderItem->id]
            );

            $orderItem->decrement_quantity_url =  route(
                'order-items.decrement-quantity',
                ['order' => $orderItem->order_id, 'order_item' => $orderItem->id]
            );
        });

        return response($order);
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
