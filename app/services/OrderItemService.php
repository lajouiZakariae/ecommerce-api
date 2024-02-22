<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;

class OrderItemService
{
    function assingOrderItemsToOrder(Order $order, array $orderItems)
    {
        $productIdsInOrderItemsCollection = collect($orderItems)->pluck('product_id');

        $products = Product::query()->whereIn('id', $productIdsInOrderItemsCollection)->get(['id', 'price']);

        $assignProductPriceToOrderItem = function ($orderItem) use ($products) {

            $productFound = $products->first(
                fn (Product $product) => $product->id === $orderItem['product_id']
            );

            $orderItem['product_price'] = $productFound->price;
            return $orderItem;
        };

        $orderItems = array_map($assignProductPriceToOrderItem, $orderItems);

        $order->orderItems()->createMany($orderItems);
    }
}
