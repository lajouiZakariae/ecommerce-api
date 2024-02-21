<?php

namespace App\Services;

use App\Events\OrderItemsHasChanged;
use App\Exceptions\ResourceNotCreatedException;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use DB;
use Exception;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class OrderService
{
    public function getAllFilteredOrders(array $filters)
    {
        $orders = Order::query()
            ->with([
                "client:id,first_name,last_name",
                "orderItems" => [
                    "product:id,title,price"
                ],
            ])
            ->get();

        $orders = $orders->map(fn ($order) => ($this->calculateTotalsOfOrderAndOrderItems($order)));

        return OrderResource::collection($orders);
    }

    /**
     * Get an order by its ID or throw a ResourceNotFound Exception
     *
     * @param int $id The ID of the order.
     * @return Order The order instance.
     * @throws Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function getBydId(int $id): Order
    {
        $order = Order::with([
            "paymentMethod",
            "client",
            "orderItems" => [
                "product:id,title,price" => [
                    "thumbnail"
                ]
            ],
        ])->find($id);

        if ($order === null)
            throw new ResourceNotFoundException("Order Not Found");

        return $this->calculateTotalsOfOrderAndOrderItems($order);
    }

    /**
     * Creates a new Order
     */
    function create(array $data): Order
    {
        $order = DB::transaction(function () use ($data) {
            $order = new Order([
                "client_id" => $data["client_id"],
                "status" => $data["status"],
                "coupon_code_id" => $data["coupon_code_id"],
                "payment_method_id" => $data["payment_method_id"],
            ]);

            if (!$order->save()) throw new ResourceNotCreatedException("Order could not be created");

            $order->orderItems()->createMany($data["order_items"]);

            OrderItemsHasChanged::dispatch($order);

            return $order;
        });


        return $order->load([
            'orderItems' => [
                'product'
            ]
        ]);
    }


    /**
     * Calculates Totals of Order and Order Items and returns the order.
     * @param \App\Models\Order $order
     * @return \App\Models\Order
     */
    private function calculateTotalsOfOrderAndOrderItems(Order $order): Order
    {
        $order->orderItems->each(function (OrderItem $orderItem) {
            $total_price = $orderItem->product->price * $orderItem->quantity;
            $orderItem->total_price = round($total_price, 2);
        });

        $total_quantity = $order->orderItems->reduce(
            fn ($acc, OrderItem $orderItem) => $acc + $orderItem->quantity,
            0
        );

        $order->total_quantity = $total_quantity;

        $total_unit_price  = $order->orderItems->reduce(
            fn ($acc, OrderItem $orderItem) => $acc + $orderItem->product->price,
            0
        );

        $order->total_unit_price = round($total_unit_price, 2);

        $total_price = $order->orderItems->reduce(function ($acc, OrderItem $orderItem) {
            return $acc + ($orderItem->quantity * $orderItem->product->price);
        }, 0);

        $order->total_price = round($total_price, 2);

        return $order;
    }
}
