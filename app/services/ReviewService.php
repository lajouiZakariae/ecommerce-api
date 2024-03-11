<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ReviewService
{
    /**
     * @param int $productId
     * 
     * @return LengthAwarePaginator
     */
    public function getAllReviewsOfProduct(int $productId): LengthAwarePaginator
    {
        $product = Product::find($productId);

        if ($product === null) throw new ResourceNotFoundException('Product Not Found');

        return $product->reviews()->paginate(10);
    }
}
