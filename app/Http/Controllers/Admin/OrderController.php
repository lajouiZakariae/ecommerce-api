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
    public function __construct(
        private OrderService $orderService,
    ) {
    }

    /**
     * Display a listing of the orders.
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
     * Store a newly created order in storage.
     *
     * @param  \App\Http\Requests\OrderStoreRequest  $request
     * @return \App\Http\Resources
     */
    public function store(OrderStoreRequest $request)
    {
        $data = $request->validated();

        return  OrderResource::make(
            $this->orderService->placeOrder($data)
        );
    }

    /**
     * Display the specified order.
     *
     * @param int $orderId
     * @return OrderResource
     */
    public function show(int $orderId): OrderResource
    {
        $order = $this->orderService->getOrderBydIdWithTotalsCalculated($orderId);

        return OrderResource::make($order);
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \App\Http\Requests\OrderUpdateRequest  $request
     * @param  int $orderId
     * @return \Illuminate\Http\Response
     */
    public function update(OrderUpdateRequest $request, int $orderId): Response
    {
        $data = $request->validated();

        $this->orderService->updateOrderUseCase($orderId, $data);

        return response()->noContent();
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $orderId): Response
    {
        $this->orderService->deleteOrderById($orderId);

        return response()->noContent();
    }

    /**
     * Cancel the specified order from storage.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\Response
     */
    public function cancelOrder(int $orderId): Response
    {
        $this->orderService->cancelOrder($orderId);

        return response()->noContent();
    }
}
