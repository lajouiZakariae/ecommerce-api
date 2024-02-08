<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'paid' => $this->paid,
            'delivery_date' => $this->delivery_date,
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'payment_method' =>  new PaymentMethodResource(
                $this->whenLoaded('paymentMethod')
            ),
            'store' => new StoreResource(
                $this->whenLoaded('store')
            ),
            'purchase_items_count' => $this->whenCounted('purchaseItems')
        ];
    }
}
