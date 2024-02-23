<?php

namespace App\Services;

use App\Enums\Status;
use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use DB;

class OrderService
{
    private $notFoundMessage = 'Order Not Found';

    public function __construct(
        private OrderItemService $orderItemService
    ) {
    }

    /**
     * Get a list of paginated orders
     * that matches the provided orderFilters
     * 
     * @param $orderFilters
     * @return Collection<int,Order>
     */
    public function getAllFilteredOrdersWithTotalsCalculated(array $orderFilters): Collection
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

        return $orders;
    }

    /**
     * Get an order by its ID or throw a ResourceNotFound Exception
     *
     * @param int $orderId The ID of the order.
     * @return Order The order instance.
     * @throws Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function getOrderBydIdWithTotalsCalculated(int $orderId): Order
    {
        $order = Order::with([
            "paymentMethod",
            "client",
            "orderItems" => [
                "product:id,title,price" => [
                    "thumbnail"
                ]
            ],
        ])->find($orderId);

        if ($order === null)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return $this->calculateTotalsOfOrderAndOrderItems($order);
    }

    /**
     * @param array $orderPayload
     * @return Order 
     */
    public function placeOrderWithOrderItems(array $orderPayload): Order
    {
        $order = DB::transaction(function () use ($orderPayload) {

            $order = new Order([
                "client_id" => $orderPayload["client_id"],
                "status" => Status::PENDING,
                "coupon_code_id" => $orderPayload["coupon_code_id"],
                "payment_method_id" => $orderPayload["payment_method_id"],
            ]);

            $saved = $order->save();

            if (!$saved) throw new BadRequestException("Order could not be created");

            return $this->orderItemService->addOrderItemsToOrder($order, $orderPayload['order_items']);
        });


        return $order->load([
            'orderItems' => [
                'product'
            ]
        ]);
    }

    /**
     * Update an order by its ID.
     *
     * @param int $orderId
     * @param array $data
     *
     * @return bool
     */
    public function updateOrderUseCase(int $orderId, array $data): bool
    {
        return $this->updateOrder($orderId, $data);
    }

    /**
     * Update an order by its ID.
     *
     * @param int $orderId
     * @param array $data
     *
     * @return bool
     */
    private function updateOrder(int $orderId, array $data): bool
    {
        $affectedRowCount = Order::where('id', $orderId)->update($data);

        if ($affectedRowCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * Cancel an Order by it's ID
     * @param int $id
     * 
     * @return bool
     */
    public function cancelOrderById(int $orderId): bool
    {
        $orderToBeCanceled = Order::find($orderId, ['status']);

        if (!$orderToBeCanceled)
            throw new ResourceNotFoundException($this->notFoundMessage);

        $uncancelableStatusEnumList = [
            Status::CANCELLED,
            Status::SHIPPING,
            Status::DELIVERED,
            Status::DELIVERY_ATTEMPT,
            Status::RETURN_TO_SENDER
        ];

        $uncancelableStatusList = array_column($uncancelableStatusEnumList, 'value');

        if (in_array($orderToBeCanceled->status, $uncancelableStatusList))
            throw new BadRequestException("Order Can't be Cancelled");

        return $this->updateOrder($orderId, ['status' => Status::CANCELLED]);
    }

    /**
     * @param int $id The ID of the product to be deleted.
     * @return bool
     */
    public function deleteOrderById(int $id)
    {
        $affectedRowsCount = Order::where('id', $id)->delete();

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * @param \App\Models\Order $order
     * @return \App\Models\Order
     */
    private function calculateTotalsOfOrderAndOrderItems(Order $order): Order
    {
        $hasOrderItems = $order->orderItems->count() > 0;

        if ($hasOrderItems) {
            $order->orderItems->each(function (OrderItem $orderItem) {
                $total_price = $orderItem->product_price * $orderItem->quantity;
                $orderItem->total_price = round($total_price, 2);
            });
        }

        $totalQuantity = $hasOrderItems
            ?  $order->orderItems->reduce(
                fn ($acc, OrderItem $orderItem) => $acc + $orderItem->quantity,
                0
            )
            : 0;

        $order->total_quantity = $totalQuantity;

        $totalUnitPrice  = $hasOrderItems
            ? $order->orderItems->reduce(
                fn ($acc, OrderItem $orderItem) => $acc + $orderItem->product_price,
                0
            )
            : 0;

        $order->total_unit_price = round($totalUnitPrice, 2);

        $avgUnitPrice = $hasOrderItems ? ($totalUnitPrice) / $order->orderItems->count() : 0;

        $order->avg_unit_price = round($avgUnitPrice, 2);

        $total_price = $hasOrderItems
            ? $order->orderItems->reduce(
                fn ($acc, OrderItem $orderItem) => $acc + ($orderItem->quantity * $orderItem->product_price),
                0
            )
            : 0;

        $order->total_price = round($total_price, 2);

        return $order;
    }
}
