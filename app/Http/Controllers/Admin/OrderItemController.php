<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItemStoreRequest;
use App\Http\Resources\Admin\OrderItemResource;
use App\Models\OrderItem;
use App\Services\OrderItemService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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
    public function store(OrderItemStoreRequest $request)
    {
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
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    // public function update($order, OrderItem $orderItem): Response
    // {
    //     $data = request()->validate([
    //         'product_id' => ['required', 'exists:products,id'],
    //         'quantity' => ['required', 'integer'],
    //     ]);

    //     $orderItem->update($data);

    //     return response()->noContent();
    // }

    /**
     * Remove the specified order item from storage for a specific order.
     *
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    // public function destroy($order, OrderItem $orderItem): Response
    // {
    //     $orderItem->delete();

    //     return response()->noContent();
    // }

    /**
     * Increment the quantity of the specified order item for a specific order.
     *
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    // public function incrementQuantity($orderId, OrderItem $orderItem): Response
    // {
    //     if ($orderItem->quantity === 18_446_744_073_709_551_615) {
    //         return response(['error' => 'maximum value reached'], Response::HTTP_BAD_REQUEST);
    //     }

    //     $orderItem->increment('quantity');

    //     return response()->noContent();
    // }

    /**
     * Decrement the quantity of the specified order item for a specific order.
     *
     * @param  mixed  $order
     * @param  \App\Models\OrderItem  $orderItem
     * @return \Illuminate\Http\Response
     */
    // public function decrementQuantity($orderId, OrderItem $orderItem): Response
    // {
    //     if ($orderItem->quantity === 0) {
    //         return response(['error' => 'minimum value reached'], Response::HTTP_BAD_REQUEST);
    //     }

    //     $orderItem->decrement('quantity');

    //     return response()->noContent();
    // }
}
