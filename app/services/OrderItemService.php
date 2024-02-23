<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class OrderItemService
{
    private $notFoundMessage = "Order Item Not Found";

    /**
     * Creates a list of order items in the database
     * and assigns them to aspecific Order
     * 
     * @param Order $order
     * @param array $orderItems
     * 
     * @return Order
     */
    function assingOrderItemsToOrder(Order $order, array $orderItems): Order
    {
        $productIdsInOrderItemsCollection = collect($orderItems)->pluck('product_id');

        $products = Product::whereIn('id', $productIdsInOrderItemsCollection)->get(['id', 'price']);

        $assignProductPriceToOrderItem = function ($orderItem) use ($products) {

            $productFound = $products->first(
                fn (Product $product) => $product->id === $orderItem['product_id']
            );

            $orderItem['product_price'] = $productFound->price;
            return $orderItem;
        };

        $orderItems = array_map($assignProductPriceToOrderItem, $orderItems);

        $order->orderItems()->createMany($orderItems);
        return $order;
    }

    /**
     * @param int $orderItemId
     * @param array $orderItemPayload
     *
     * @return bool
     */
    private function updateOrderItem(int $orderItemId, array $orderItemPayload): bool
    {
        $affectedRowCount = OrderItem::where('id', $orderItemId)->update($orderItemPayload);

        if ($affectedRowCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * Delete an order item By it's ID from the storage
     * 
     * @param int $id
     * 
     * @return bool
     */
    function deleteById(int $id)
    {
        $affectedRowsCount = OrderItem::where('id', $id)->delete();

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }
}
