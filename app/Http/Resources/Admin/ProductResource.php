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
            'created_at' => $this->created_at,
            'title' => $this->title,
            'description' => $this->whenHas('description'),
            'price' => $this->price,
            'cost' => $this->whenHas('cost'),
            // 'quantity' =>
            // $this->whenNotNull(
            //     $this->whenAggregated('inventory', 'quantity', 'sum', $this->inventory_sum_quantity, 0)
            // ),
            'quantity' => $this->quantity,
            'published' => $this->whenHas('published'),
            'category_id' => $this->whenHas('category_id'),
            'thumbnail' =>  new ImageResource($this->whenLoaded('thumbnail')),
        ];
    }
}
