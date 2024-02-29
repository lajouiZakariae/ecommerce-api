<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CouponCodeResource;
use App\Rules\ValidIntegerTypeRule;
use App\Services\CouponCodeService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class CouponCodeController extends Controller
{
    public function __construct(private CouponCodeService $couponCodeService)
    {
    }

    public function index(): ResourceCollection
    {
        $couponCodes = $this->couponCodeService->getAllCouponCodes([
            'sortBy' => request()->input("sortBy")
        ]);

        return CouponCodeResource::collection($couponCodes);
    }

    public function store(): CouponCodeResource
    {
        $couponCodePayload = [
            'code' => request()->input('code'),
            'amount' => request()->input('amount'),
        ];

        $couponCodeValidator = validator()->make($couponCodePayload, [
            'code' => ['required', 'min:1', 'max:255', 'unique:coupon_codes,code'],
            'amount' => ['required', 'integer', new ValidIntegerTypeRule, 'min:1', 'max:100'],
        ]);

        $validatedCouponCodePayload = $couponCodeValidator->validate();

        return CouponCodeResource::make($this->couponCodeService->createCouponCode($validatedCouponCodePayload));
    }

    public function update(int $couponCodeId): Response
    {
        $couponCodePayload = [
            'code' => request()->input('code'),
            'amount' => request()->input('amount'),
        ];

        $couponCodeValidator = validator()->make($couponCodePayload, [
            'code' => ['required', 'min:1', 'max:255', 'unique:coupon_codes,code'],
            'amount' => ['required', 'integer', new ValidIntegerTypeRule, 'min:1', 'max:100'],
        ]);

        $validatedCouponCodePayload = $couponCodeValidator->validate();

        $this->couponCodeService->updateCouponCode($couponCodeId, $validatedCouponCodePayload);

        return response()->noContent();
    }

    public function destroy(int $couponCodeId): Response
    {
        $this->couponCodeService->deleteCouponCodeById($couponCodeId);

        return response()->noContent();
    }
}
