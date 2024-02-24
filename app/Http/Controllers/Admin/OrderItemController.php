<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItemStoreRequest;
use App\Http\Requests\OrderItemUpdateRequest;
use App\Http\Resources\Admin\OrderItemResource;
use App\Services\OrderItemService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class OrderItemController extends Controller
{
    public function __construct(private OrderItemService $orderItemService)
    {
    }

    /**
     * @param int $orderId
     * @return ResourceCollection
     */
    public function index(int $orderId): ResourceCollection
    {
        return OrderItemResource::collection(
            $this->orderItemService->getAllOrderItemsOfOrder($orderId)
        );
    }

    /**
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function store(OrderItemStoreRequest $request, int $orderId)
    {
        $orderItemPayload = $request->validated();

        $orderItems = $this->orderItemService->createOrderItemsInOrder($orderId, $orderItemPayload);

        return response(OrderItemResource::collection($orderItems), Response::HTTP_CREATED);
    }

    /**
     * Display the specified order item.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    public function show(int $orderId, int $orderItemId)
    {
        $orderItem = $this->orderItemService->getOrderItemOfOrderById($orderId, $orderItemId);

        return new OrderItemResource($orderItem);
    }

    /**
     * Update the specified order item in storage for a specific order.
     * 
     * @param  OrderItemUpdateRequest $request 
     * @param  int $orderId 
     * @param  int $orderItemId
     * @return \Illuminate\Http\Response
     */
    public function update(OrderItemUpdateRequest $request, int $orderId, int $orderItemId): Response
    {
        $this->orderItemService->updateOrderItemOfOrder(
            $orderId,
            $orderItemId,
            $request->validated()
        );

        return response()->noContent();
    }

    /**
     * Remove the specified order item from storage for a specific order.
     * @param int $orderId
     * @param int $orderItemId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $orderId, int $orderItemId): Response
    {
        $this->orderItemService->deleteOrderItemOfOrderById($orderId, $orderItemId);

        return response()->noContent();
    }
}
