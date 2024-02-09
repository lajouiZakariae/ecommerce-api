<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Null_;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'product' => $this->whenLoaded('product'),
            "url" => route(
                'order-items.show',
                ['order' => $this->order_id, 'order_item' => $this->id]
            ),
            "decrement_quantity_url" => route('order-items.decrement-quantity', [
                'order' => $this->order_id,
                'order_item' => $this->id
            ]),
            "increment_quantity_url" => route('order-items.increment-quantity', [
                'order' => $this->order_id,
                'order_item' => $this->id
            ]),
        ];
    }
}
