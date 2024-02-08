<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CouponCodeResource;
use App\Models\CouponCode;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;

/**
 * @group Coupon Codes
 */
#[ApiResource('coupon-codes')]
class CouponCodeController extends Controller
{
    /**
     * Display a listing of coupon codes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $couponCodes = CouponCode::all();

        return response(CouponCodeResource::collection($couponCodes));
    }

    /**
     * Store a newly created coupon code in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(): Response
    {
        $data = request()->validate([
            'code' => ['required', 'string', 'min:1', 'max:255'],
            'amount' => ['required', 'integer', 'min:0', 'max:100']
        ]);

        $couponCode = CouponCode::create($data);

        return response(new CouponCodeResource($couponCode), Response::HTTP_CREATED);
    }

    /**
     * Display the specified coupon code.
     *
     * @param  \App\Models\CouponCode  $couponCode
     * @return \Illuminate\Http\Response
     */
    public function show(CouponCode $couponCode): Response
    {
        return response(new CouponCodeResource($couponCode));
    }

    /**
     * Update the specified coupon code in storage.
     *
     * @param  \App\Models\CouponCode  $couponCode
     * @return \Illuminate\Http\Response
     */
    public function update(CouponCode $couponCode): Response
    {
        $data = request()->validate([
            'code' => ['string', 'min:1', 'max:255'],
            'amount' => ['integer', 'min:0', 'max:100']
        ]);

        $couponCode->update($data);

        return response()->noContent();
    }

    /**
     * Remove the specified coupon code from storage.
     *
     * @param  \App\Models\CouponCode  $couponCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(CouponCode $couponCode): Response
    {
        $couponCode->delete();

        return response()->noContent();
    }
}
