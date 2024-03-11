<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewStoreRequest;
use App\Http\Requests\Admin\ReviewUpdateRequest;
use App\Http\Resources\Admin\ReviewResource;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;


class ReviewController extends Controller
{
    public function __construct(private ReviewService $reviewService)
    {
    }

    /**
     * Display a listing of reviews.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): ResourceCollection
    {
        $reviews = Review::paginate(10);

        return ReviewResource::collection($reviews);
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  \App\Http\Requests\Admin\ReviewStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReviewStoreRequest $request): Response
    {
        $review = Review::create($request->validated());

        return response(new ReviewResource($review), Response::HTTP_CREATED);
    }

    /**
     * @param int $reviewId
     * 
     * @return ReviewResource
     */
    public function show(int $reviewId): ReviewResource
    {
        return ReviewResource::make($this->reviewService->getReviewById($reviewId));
    }

    /**
     * @param ReviewUpdateRequest $request
     * @param int $reviewId
     * 
     * @return ReviewResource
     */
    public function update(ReviewUpdateRequest $request, int $reviewId): ReviewResource
    {
        $updatedReview = $this->reviewService->updateReview($reviewId, $request->validated());
        return ReviewResource::make($updatedReview);
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review): Response
    {
        $review->delete();

        return response()->noContent();
    }

    /**
     * @param int $productId
     * 
     * @return ResourceCollection
     */
    public function productReviews(int $productId): ResourceCollection
    {
        return ReviewResource::collection($this->reviewService->getAllReviewsOfProduct($productId));
    }
}
