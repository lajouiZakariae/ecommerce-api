<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ReviewService
{
    private $notFoundMessage = "Review Not Found";

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

    /**
     * @param int $reviewId
     * 
     * @return Review
     */
    public function getReviewById(int $reviewId): Review
    {
        $review = Review::find($reviewId);

        if ($review === null) throw new ResourceNotFoundException($this->notFoundMessage);

        return $review;
    }

    /**
     * @param int $reviewId
     * @param array $reviewPayload
     * 
     * @return Review
     */
    public function updateReview(int $reviewId, array $reviewPayload): Review
    {
        $affectedRowsCount = Review::where('id', $reviewId)->update($reviewPayload);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return Review::find($reviewId);
    }

    /**
     * @param int $reviewId
     * 
     * @return void
     */
    public function deleteReviewById(int $reviewId): void
    {
        $affectedRowsCount = Review::where('id', $reviewId)->delete();

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);
    }
}
