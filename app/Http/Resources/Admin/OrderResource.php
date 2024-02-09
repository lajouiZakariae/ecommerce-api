<?php

namespace App\Http\Resources\Admin;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use League\CommonMark\Extension\TableOfContents\Normalizer\FlatNormalizerStrategy;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->whenHas('full_name'),
            'email' => $this->whenHas('email'),
            'phone_number' => $this->whenHas('phone_number'),
            'status' => $this->status,
            'delivery' => $this->delivery,
            'created_at' => $this->created_at,
            'order_items_count' => $this->whenCounted('order_items'),
            'city' => $this->whenHas('city'),
            'zip_code' => $this->whenHas('zip_code'),
            'address' => $this->whenHas('address'),
            'payment_method' => new PaymentMethodResource($this->whenLoaded('paymentMethod')),
            'order_items_count' => $this->whenCounted('orderItems'),
            'order_items_url' => $this->whenLoaded(
                'orderItems',
                route('order-items.index', ['order' => $this->id])
            ),
            'order_items' => $this->whenLoaded('orderItems', OrderItemResource::collection($this->orderItems)),
            'total_price' => $this->calculateTotalPrice(),
        ];
    }

    private function calculateTotalPrice(): ?float
    {
        return $this->whenLoaded(
            'orderItems',
            $this->orderItems->reduce(function ($acc, OrderItem $orderItem) {
                return $acc + ($orderItem->quantity * $orderItem->product->price);
            }, 0)
        );
    }
}
