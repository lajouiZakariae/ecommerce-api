<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Http\Resources\Admin\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    /**
     * Get a listing of the orders.
     *
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        return OrderResource::collection(
            $this->orderService->getAllFilteredOrdersWithTotalsCalculated([])
        );
    }

    /**
     * Store a newly created order.
     *
     * @param  OrderStoreRequest  $request
     * 
     * @return OrderResource
     */
    public function store(OrderStoreRequest $request): OrderResource
    {
        $data = $request->validated();

        return OrderResource::make(
            $this->orderService->placeOrderWithOrderItems($data)
        );
    }

    /**
     * Get a specific order.
     *
     * @param int $orderId
     * 
     * @return OrderResource
     */
    public function show(int $orderId): OrderResource
    {
        $order = $this->orderService->getOrderBydIdWithTotalsCalculated($orderId);

        return OrderResource::make($order);
    }

    /**
     * Update a specific order in storage.
     *
     * @param  OrderUpdateRequest  $request
     * @param  int $orderId
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(OrderUpdateRequest $request, int $orderId): Response
    {
        $data = $request->validated();

        $this->orderService->updateOrderUseCase($orderId, $data);

        return response()->noContent();
    }

    /**
     * Delete a specific order.
     *
     * @param  int  $orderId
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $orderId): Response
    {
        $this->orderService->deleteOrderById($orderId);

        return response()->noContent();
    }

    /**
     * Cancel a specific order.
     *
     * @param  int  $orderId
     * 
     * @return \Illuminate\Http\Response
     */
    public function cancelOrder(int $orderId): Response
    {
        $this->orderService->cancelOrderById($orderId);

        return response()->noContent();
    }
}
