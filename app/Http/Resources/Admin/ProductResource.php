<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => route('products.show', ['product' => $this->id]),
            'created_at' => $this->whenHas('created_at'),
            'title' => $this->title,
            'description' => $this->whenHas('description'),
            'price' => $this->price,
            'cost' => $this->whenHas('cost'),
            'quantity' => $this->whenHas('quantity', $this->quantity ? $this->quantity : 0),
            'published' => $this->whenHas('published'),
            'category_id' => $this->whenHas('category_id'),
            'thumbnail' =>  new ImageResource($this->whenLoaded('thumbnail')),
        ];
    }
}
