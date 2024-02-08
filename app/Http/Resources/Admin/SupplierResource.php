<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->whenHas('email'),
            'phone_number' => $this->whenHas('phone_number'),
            'address' => $this->whenHas('address'),
            'url' => route('suppliers.show', ['supplier' => $this->id])
        ];
    }
}
