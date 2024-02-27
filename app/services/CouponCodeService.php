<?php

namespace App\Services;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\CouponCode;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CouponCodeService
{
    private $notFoundMessage = "Coupon Code Not Found";

    /**
     * @return Collection
     */
    public function getAllCouponCodes(array $filters): Collection
    {
        $couponCodes = CouponCode::query();

        $couponCodes =  $filters['sortBy'] === "oldest"
            ? $couponCodes->oldest()
            : $couponCodes->latest();

        return $couponCodes->get();
    }

    /**
     * @return Collection
     */
    public function getStoreById(int $couponCodeId): Collection
    {
        $couponCode = CouponCode::find($couponCodeId);

        if ($couponCode === null) throw new ResourceNotFoundException($this->notFoundMessage);

        return $couponCode;
    }


    /**
     * @param array $storePayload
     * @return CouponCode
     */
    public function createCouponCode(array $couponCodePayload): CouponCode
    {
        $couponCode = new CouponCode($couponCodePayload);

        if (!$couponCode->save()) throw new BadRequestException("Coupon Code Could not be created");

        return $couponCode;
    }

    /**
     * @param int $couponCodeId
     * @param array $couponCodePayload
     * @return bool
     */
    public function updateCouponCode(int $couponCodeId, array $couponCodePayload): bool
    {
        $affectedRowsCount = CouponCode::where('id', $couponCodeId)->update($couponCodePayload);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }


    /**
     * @param int $storeId
     * @return bool
     */
    public function deleteCouponCodeById(int $couponCodeId): bool
    {
        $affectedRowsCount = CouponCode::destroy($couponCodeId);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }
}
