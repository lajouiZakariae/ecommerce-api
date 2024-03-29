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
            "url" => route(
                'order-items.show',
                ['order' => $this->order_id, 'order_item' => $this->id]
            ),
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product_price' => $this->product_price,
            'quantity' => $this->quantity,
            'total_price' => $this->whenHas('total_price'),
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
