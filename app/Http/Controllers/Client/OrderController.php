<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStoreRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Http\Resources\Admin\OrderCollection;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;

class OrderController extends Controller
{
    public function store(OrderStoreRequest $request): Response
    {
        $data = $request->validated();

        /** @var Order */
        $order = Order::create($data);

        $order_items = $request->collect('order_items');

        $order_items_models = $order_items->map(fn ($order_item) => [
            'order_id' => $order->id,
            'product_id' => $order_item['product_id'],
            'quantity' => $order_item['quantity'],
        ]);

        $order->orderItems()->createMany($order_items_models);

        return response($order->load('orderItems'), Response::HTTP_CREATED);
    }
}
