<?php

namespace App\Observers;

use App\Models\OrderItem;

class OrderItemObserver
{
    /**
     * Handle the OrderItem "creating" event.
     */
    public function creating(OrderItem $orderItem): void
    {
        $orderItem->total_price = round($orderItem->quantity * $orderItem->product->price, 2);
    }

    /**
     * Handle the OrderItem "updating" event.
     */
    public function updating(OrderItem $orderItem): void
    {
        $orderItem->total_price = round($orderItem->quantity * $orderItem->product->price, 2);
    }


    /**
     * Handle the OrderItem "created" event.
     */
    public function created(OrderItem $orderItem): void
    {
    }

    /**
     * Handle the OrderItem "updated" event.
     */
    public function updated(OrderItem $orderItem): void
    {
    }

    /**
     * Handle the OrderItem "deleted" event.
     */
    public function deleted(OrderItem $orderItem): void
    {
        //
    }
}
