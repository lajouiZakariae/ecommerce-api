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
            'id' => $this->id,
            'email' => ($this->email),
            'status' => $this->status,
            'delivery' => $this->delivery,
            'created_at' => $this->created_at,
            'order_items_count' => $this->whenCounted('order_items'),
            'full_name' => $this->whenHas('full_name'),
            'phone_number' => $this->whenHas('phone_number'),
            'city' => $this->whenHas('city'),
            'zip_code' => $this->whenHas('zip_code'),
            'address' => $this->whenHas('address'),
            'order_items' => $this->whenLoaded('orderItems'),
            'payment_method' => $this->whenLoaded('paymentMethod', new PaymentMethodResource($this->paymentMethod)),
        ];
    }
}
