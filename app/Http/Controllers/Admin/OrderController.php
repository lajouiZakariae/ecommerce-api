<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStoreRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;

/**
 * @group Orders
 */
// #[ApiResource('orders')]
class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return response($this->orderService->getAllFilteredOrders([]));
    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \App\Http\Requests\Admin\OrderStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = [
            "client_id" => 1,
            "status" => fake()->randomElement(["pending", "in transit", "delivered", "delivery attempt", "cancelled", "return to sender"]),
            "coupon_code_id" => 2,
            "payment_method_id" => fake()->numberBetween(1, 2),
            "order_items" => [
                ["product_id" => 1, "quantity" => 2],
                ["product_id" => 2, "quantity" => 3],
            ]
        ];

        return $this->orderService->create($data);
    }

    /**
     * Display the specified order.
     *
     * @param int $order_id
     * @return \Illuminate\Http\Response
     */
    public function show(int $order_id): Response
    {
        $order = $this->orderService->getBydId($order_id);

        return response(new OrderResource($order));
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \App\Http\Requests\Admin\OrderUpdateRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderUpdateRequest $request, Order $order): Response
    {
        $data = $request->validated();

        $order->update($data);

        return response()->noContent();
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order): Response
    {
        $order->delete();

        return response()->noContent();
    }
}
