<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => route('clients.show', ['client' => $this->id]),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->whenHas('email'),
            'phone_number' => $this->whenHas('phone_number'),
            'address' => $this->whenHas('address'),
            'city' => $this->whenHas('city'),
            'zip_code' => $this->whenHas('zip_code'),
        ];
    }
}
