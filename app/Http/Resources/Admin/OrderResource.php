<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "url" => route('orders.show', ['order' => $this->id]),
            "created_at" => $this->created_at,
            "client" => new ClientResource($this->whenLoaded('client')),
            "status" => $this->status,
            "total_price" => $this->whenHas('total_price'),
            "total_quantity" => $this->whenHas('total_quantity'),
            "total_unit_price" => $this->whenHas('total_unit_price'),
            "avg_unit_price" => $this->whenHas('avg_unit_price'),
            "order_items" => OrderItemResource::collection($this->whenLoaded('orderItems')),
        ];
    }
}
