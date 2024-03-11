<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewStoreRequest;
use App\Http\Requests\Admin\ReviewUpdateRequest;
use App\Http\Resources\Admin\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\Get;


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
    public function index(): Response
    {
        $reviews = Review::all();

        return response(ReviewResource::collection($reviews));
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
     * Display the specified review.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review): Response
    {
        return response(new ReviewResource($review));
    }

    /**
     * Update the specified review in storage.
     *
     * @param  \App\Http\Requests\Admin\ReviewUpdateRequest  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(ReviewUpdateRequest $request, Review $review): Response
    {
        $data = $request->validated();

        $review->update($data);

        return response()->noContent();
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
