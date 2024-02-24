<?php

namespace App\Services;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class OrderItemService
{
    private $notFoundMessage = "Order Item Not Found";

    public function getAllOrderItemsOfOrder(int $orderId)
    {
        $order = Order::exists($orderId);

        if (!$order) throw new ResourceNotFoundException("Order Not Found");

        $orderItems = OrderItem::query()
            ->where('order_id', $orderId)
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
     * @param int $orderId
     * @param array $orderItemsPayload
     * 
     * @return Collection<int,OrderItem>
     */
    public function createOrderItemsInOrder($orderId, $orderItemsPayload): SupportCollection
    {
        $order = Order::exists($orderId);

        if (!$order) throw new ResourceNotFoundException("Order Not Found");

        $productIdsInOrderItemsCollection = collect($orderItemsPayload)->pluck('product_id');

        $productsExistsInOrder = Product::whereIn('id', $productIdsInOrderItemsCollection)->get(['id', 'price']);

        $orderItems = collect($orderItemsPayload)
            ->map(function ($orderItem) use ($productsExistsInOrder) {

                $productFound = $productsExistsInOrder->first(
                    fn (Product $product) => $product->id === $orderItem['product_id']
                );

                $orderItem['product_price'] = $productFound->price;

                return $orderItem;
            })
            ->map(function ($orderItemWithPrice) use ($orderId) {
                $orderItemWithPrice['order_id'] = $orderId;

                $orderItem = new OrderItem($orderItemWithPrice);

                $orderItem->save();

                return $orderItem;
            });

        return $orderItems;
    }

    /**
     * @param array $orderItemPayload
     *
     * @return OrderItem
     */
    public function addOrderItemToOrder(int $orderId, array $orderItemPayload): OrderItem
    {
        $orderItem = new OrderItem($orderItemPayload);

        $order = Order::exists($orderId);

        if (!$order) throw new ResourceNotFoundException('Order Not Found');

        $foundProduct = Product::query()->find(
            $orderItemPayload['product_id'],
            ['id', 'price']
        );

        $orderItem['order_id'] = $orderId;
        $orderItem['product_price'] = $foundProduct->price;

        $saved = $orderItem->save();

        if (!$saved) throw new BadRequestException("Order Item Could not be Created");

        return $orderItem;
    }


    /**
     * @param int $orderId
     * @param int $orderItemId
     * @param array $orderItemPayload
     *
     * @return bool
     */
    public function updateOrderItemOfOrder(int $orderId, int $orderItemId, array $orderItemPayload): bool
    {
        $affectedRowCount = OrderItem::query()
            ->where('id', $orderItemId)
            ->where('order_id', $orderId)
            ->update($orderItemPayload);

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
    public function deleteOrderItemOfOrderById(int $orderId, int $orderItemId)
    {
        $affectedRowsCount = OrderItem::query()
            ->where('order_id', $orderId)
            ->where('id', $orderItemId)
            ->delete();

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }
}
