<?php

namespace App\Services;

use App\Enums\Status;
use App\Exceptions\ResourceNotCreatedException;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use DB;
use phpDocumentor\Reflection\PseudoTypes\True_;

class OrderService
{
    private $notFoundMessage = 'Order Not Found';

    public function __construct(
        private OrderItemService $orderItemService
    ) {
    }

    /**
     * Get a list of paginated orders
     * that matches the provided filters
     * @param $filters
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function getAllFilteredOrders(array $filters): Collection
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
            throw new ResourceNotFoundException($this->notFoundMessage);

        return $this->calculateTotalsOfOrderAndOrderItems($order);
    }

    /**
     * Creates a new Order
     */
    function create(array $data)
    {
        $order = DB::transaction(function () use ($data) {

            $order = new Order([
                "client_id" => $data["client_id"],
                "status" => Status::PENDING,
                "coupon_code_id" => $data["coupon_code_id"],
                "payment_method_id" => $data["payment_method_id"],
            ]);

            $saved = $order->save();

            if (!$saved) throw new ResourceNotCreatedException("Order could not be created");

            return $this->orderItemService->assingOrderItemsToOrder($order, $data['order_items']);
        });


        return $order->load([
            'orderItems' => [
                'product'
            ]
        ]);
    }

    function update(int $order_id, array $data): bool
    {
        $affectedRowCount = Order::where('id', $order_id)->update($data);

        if ($affectedRowCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * Delete a product by its ID.
     *
     * @param int $id The ID of the product to be deleted.
     *
     * @return bool
     */
    function deleteById(int $id)
    {
        $affectedRowsCount = Order::where('id', $id)->delete();

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * Cancel an Order by it's ID
     * @param int $id
     * 
     * @return bool
     */
    function cancelOrder(int $order_id): bool
    {
        return $this->update($order_id, ['status' => Status::CANCELLED]);
    }

    /**
     * Calculates Totals of Order and Order Items and returns the order.
     * @param \App\Models\Order $order
     * @return \App\Models\Order
     */
    private function calculateTotalsOfOrderAndOrderItems(Order $order): Order
    {
        $order->orderItems->each(function (OrderItem $orderItem) {
            $total_price = $orderItem->product_price * $orderItem->quantity;
            $orderItem->total_price = round($total_price, 2);
        });

        $total_quantity = $order->orderItems->reduce(
            fn ($acc, OrderItem $orderItem) => $acc + $orderItem->quantity,
            0
        );

        $order->total_quantity = $total_quantity;

        $total_unit_price  = $order->orderItems->reduce(
            fn ($acc, OrderItem $orderItem) => $acc + $orderItem->product_price,
            0
        );

        $order->total_unit_price = round($total_unit_price, 2);

        $avg_unit_price = ($total_unit_price) / $order->orderItems->count();

        $order->avg_unit_price = round($avg_unit_price, 2);

        $total_price = $order->orderItems->reduce(function ($acc, OrderItem $orderItem) {
            return $acc + ($orderItem->quantity * $orderItem->product_price);
        }, 0);

        $order->total_price = round($total_price, 2);

        return $order;
    }
}
