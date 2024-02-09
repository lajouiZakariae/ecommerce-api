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
            'created_at' => $this->created_at,
            'title' => $this->title,
            'description' => $this->whenHas('description'),
            'price' => $this->price,
            'cost' => $this->whenHas('cost'),
            'stock_quantity' => $this->whenHas('stock_quantity'),
            'published' => $this->whenHas('published'),
            'category_id' => $this->whenHas('category_id'),
            'store_id' => $this->whenHas('store_id'),
            'thumbnail' => $this->whenLoaded('thumbnail', new ImageResource($this->thumbnail)),
            'url' => route('products.show', ['product' => $this->id]),
        ];
    }
}
