<?php

namespace App\Listeners;

use App\Events\OrderItemsHasChanged;
use App\Exceptions\ResourceNotCreatedException;
use App\Models\OrderItem;

class UpdateTotalsOfOrder
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(OrderItemsHasChanged $event): void
    {
        // Calculate Totals

        dd($event->order->orderItems()->getQuery()->withSum('product AS total_quantity', 'price')->get('total_quantity')->toArray());

        $orderItems = $event->order->orderItems()->get();

        $total_quantity = $orderItems->reduce(
            fn ($acc, OrderItem $orderItem) => $acc + $orderItem->quantity,
            0
        );

        $event->order->total_quantity = $total_quantity;

        $total_price_per_unit  = $orderItems->reduce(
            fn ($acc, OrderItem $orderItem) => $acc + $orderItem->product->price,
            0
        );

        $event->order->total_price_per_unit = round($total_price_per_unit, 2);

        $total_price = $orderItems->reduce(function ($acc, OrderItem $orderItem) {
            return $acc + ($orderItem->quantity * $orderItem->product->price);
        }, 0);

        $event->order->total_price = round($total_price, 2);

        if (!$event->order->save()) throw new ResourceNotCreatedException("Order could not be saved");
    }
}
