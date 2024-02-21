<?php

namespace App\Services;

use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class OrderService
{

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

    public function getAllFilteredOrders(array $filters)
    {
        $orders = Order::query()
            ->with([
                'client:id,first_name,last_name',
                'orderItems' => [
                    'product:id,title,price'
                ],
            ])
            ->get();

        $orders->map(fn ($order) => ($this->calculateTotalsOfOrderAndOrderItems($order)));

        return OrderResource::collection($orders);
    }

    public function getBydId(int $id): Order
    {
        $order = Order::with([
            'paymentMethod',
            'client',
            'orderItems' => [
                'product:id,title,price' => [
                    'thumbnail'
                ]
            ],
        ])->find($id);

        if ($order === null)
            throw new ResourceNotFoundException("Order Not Found");

        return $this->calculateTotalsOfOrderAndOrderItems($order);
    }
}
