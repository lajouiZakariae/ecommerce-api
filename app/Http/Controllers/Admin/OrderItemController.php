<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItemsStoreRequest;
use App\Http\Requests\OrderItemUpdateRequest;
use App\Http\Resources\Admin\OrderItemResource;
use App\Models\OrderItem;
use App\Services\OrderItemService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class OrderItemController extends Controller
{
    public function __construct(private OrderItemService $orderItemService)
    {
    }

    /**
     * Get order items of a specific order 
     * 
     * @param int $orderId
     * 
     * @return ResourceCollection
     */
    public function index(int $orderId): ResourceCollection
    {
        return OrderItemResource::collection(
            $this->orderItemService->getAllOrderItemsOfOrder($orderId)
        );
    }

    /**
     * Store a newly created order item.
     * 
     * @param OrderItemsStoreRequest $request
     * @param int $orderId
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(OrderItemsStoreRequest $request, int $orderId): Response
    {
        $orderItemPayload = $request->validated('order_items');

        $orderItems = $this->orderItemService->createOrderItemsInOrder($orderId, $orderItemPayload);

        return response(OrderItemResource::collection($orderItems), Response::HTTP_CREATED);
    }

    /**
     * Get a specific order item.
     *
     * @param  Order  $order
     * @param  OrderItem  $orderItem
     * 
     * @return OrderItemResource
     */
    public function show(int $orderId, int $orderItemId): OrderItemResource
    {
        $orderItem = $this->orderItemService->getOrderItemOfOrderById($orderId, $orderItemId);

        return OrderItemResource::make($orderItem);
    }

    /**
     * Update a specific order item in storage for a specific order.
     * 
     * @param  OrderItemUpdateRequest $request
     * @param  int $orderId 
     * @param  int $orderItemId
     * 
     * @return OrderItem
     */
    public function update(OrderItemUpdateRequest $request, int $orderId, int $orderItemId): OrderItem
    {
        $updatedOrderItem = $this->orderItemService->updateOrderItemOfOrder(
            $orderId,
            $orderItemId,
            $request->validated(),
        );

        return $updatedOrderItem;
    }

    /**
     * Delete a specific order item from storage for a specific order.
     * 
     * @param int $orderId
     * @param int $orderItemId
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $orderId, int $orderItemId): Response
    {
        $this->orderItemService->deleteOrderItemOfOrderById($orderId, $orderItemId);

        return response()->noContent();
    }
}
