<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->whenHas('created_at'),
            'alt_text' => $this->alt_text,
            'url' => $this->imageUrl(),
            'product_id' => $this->product_id,
        ];
    }
}
