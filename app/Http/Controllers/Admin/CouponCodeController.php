<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CouponCodeResource;
use App\Models\CouponCode;
use App\Rules\ValidIntegerTypeRule;
use App\Services\CouponCodeService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class CouponCodeController extends Controller
{
    public function __construct(private CouponCodeService $couponCodeService)
    {
    }

    /**
     * Get a listing of the coupon codes.
     *
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $couponCodes = $this->couponCodeService->getAllCouponCodes([
            'sortBy' => request()->input("sortBy")
        ]);

        return CouponCodeResource::collection($couponCodes);
    }

    /**
     * Get a specific coupon code.
     *
     * @param  int  $couponCodeId
     * @return CouponCodeResource
     */
    public function show(int $couponCodeId): CouponCodeResource
    {
        return CouponCodeResource::make($this->couponCodeService->getCouponCodeById($couponCodeId));
    }

    /**
     * Store a newly created coupon code.
     *
     * @return CouponCodeResource
     */
    public function store(): CouponCodeResource
    {
        $this->authorize('create', CouponCode::class);

        $validatedCouponCodePayload = request()->validate([
            'code' => ['required', 'min:1', 'max:255', 'unique:coupon_codes,code'],
            'amount' => ['required', 'integer', new ValidIntegerTypeRule, 'min:1', 'max:100'],
        ]);

        return CouponCodeResource::make($this->couponCodeService->createCouponCode($validatedCouponCodePayload));
    }

    /**
     * Update a specific coupon code.
     *
     * @param  int  $couponCodeId
     * @return Response
     */
    public function update(int $couponCodeId): Response
    {
        $this->authorize('update', CouponCode::class);

        $couponCodePayload = [
            'code' => request()->input('code'),
            'amount' => request()->input('amount'),
        ];

        $validatedCouponCodePayload = request()->validate($couponCodePayload, [
            'code' => ['required', 'min:1', 'max:255', 'unique:coupon_codes,code'],
            'amount' => ['required', 'integer', new ValidIntegerTypeRule, 'min:1', 'max:100'],
        ]);

        $this->couponCodeService->updateCouponCode($couponCodeId, $validatedCouponCodePayload);

        return response()->noContent();
    }

    /**
     * Delete a specific coupon code.
     *
     * @param  int  $couponCodeId
     * @return Response
     */
    public function destroy(int $couponCodeId): Response
    {
        $this->authorize('delete', CouponCode::class);

        $this->couponCodeService->deleteCouponCodeById($couponCodeId);

        return response()->noContent();
    }
}
