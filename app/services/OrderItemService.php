<?php

namespace App\Services;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class OrderItemService
{
    private $notFoundMessage = "Order Item Not Found";

    public function getAllOrderItemsOfOrder(int $orderId)
    {
        $order = Order::find($orderId);

        if (!$order) throw new ResourceNotFoundException("Order Not Found");

        $orderItems = $order
            ->orderItems()
            ->with(['product:id,title,price' => ['thumbnail']])
            ->get();

        return $orderItems;
    }

    function getOrderItemOfOrderById(int $orderId, int $orderItemId): OrderItem
    {
        $orderItem = OrderItem::query()
            ->where('order_id', $orderId)
            ->where('id', $orderItemId)
            ->first();

        if (!$orderItem) throw new ResourceNotFoundException($this->notFoundMessage);

        return $orderItem;
    }

    /**
     * Creates a list of order items in the database
     * and assigns them to aspecific Order
     * 
     * @param Order $order
     * @param array $orderItems
     * 
     * @return Order
     */
    public function addOrderItemsToOrder(Order $order, array $orderItems): Order
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
     * @param array $orderItemPayload
     *
     * @return OrderItem
     */
    public function addOrderItemToOrder(int $orderId, array $orderItemPayload): OrderItem
    {
        $orderItem = new OrderItem($orderItemPayload);

        $order = Order::find($orderId);

        if (!$order) throw new ResourceNotFoundException('Order Not Found');

        $foundProduct = Product::query()->find(
            $orderItemPayload['product_id'],
            ['id', 'price']
        );

        $orderItem['product_price'] = $foundProduct->price;

        $saved = $orderItem->save();

        if (!$saved) throw new BadRequestException("Order Item Could not be Created");

        return $orderItem;
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
