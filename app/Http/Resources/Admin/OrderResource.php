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
            "id" => $this->id,
            "url" => route('orders.show', ['order' => $this->id]),
            "created_at" => $this->created_at,
            "status" => $this->status,
            "total_price" => $this->whenHas('total_price'),
            "client" => new ClientResource($this->whenLoaded('client')),
            "order_items" => OrderItemResource::collection($this->whenLoaded('orderItems')),
        ];
    }
}
