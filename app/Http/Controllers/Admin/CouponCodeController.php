<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CouponCodeResource;
use App\Models\CouponCode;
use App\Rules\ValidIntegerTypeRule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CouponCodeController extends Controller
{
    /**
     * Display a listing of the couponCodes.
     *
     * @return Collection<int,CouponCode>
     */
    public function index(): ResourceCollection
    {
        $couponCodesQuery = CouponCode::query();

        /**
         * TODO: Filter By ranges of amount
         */
        $couponCodesQuery = request()->input("sortBy") === "oldest"
            ? $couponCodesQuery->oldest()
            : $couponCodesQuery->latest();

        return CouponCodeResource::collection($couponCodesQuery->get());
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

        $couponCode = new CouponCode($validatedCouponCodePayload);

        if (!$couponCode->save()) throw new BadRequestException("Coupon code could not be created");

        return CouponCodeResource::make($couponCode);
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

        $affectedRowsCount = CouponCode::where('id', $couponCodeId)->update($validatedCouponCodePayload);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException("Coupon Code Not Found");

        return response()->noContent();
    }

    public function destroy(int $couponCodeId): Response
    {
        $affectedRowsCount = CouponCode::destroy($couponCodeId);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException("Coupon Code Not Found");

        return response()->noContent();
    }
}
