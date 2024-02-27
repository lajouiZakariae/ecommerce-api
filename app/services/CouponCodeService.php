<?php

namespace App\Services;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\CouponCode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use LengthException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CouponCodeService
{
    private $notFoundMessage = "Coupon Code Not Found";

    /**
     *
     * @param  mixed $filters
     * @return Collection
     */
    public function getAllCouponCodes(array $filters): LengthAwarePaginator
    {
        $couponCodes = CouponCode::query();

        $couponCodes =  $filters['sortBy'] === "oldest"
            ? $couponCodes->oldest()
            : $couponCodes->latest();

        return $couponCodes->paginate(10);
    }


    /**
     * @param  mixed $couponCodeId
     * 
     * @return Collection
     * @throws ResourceNotFoundException
     */
    public function getCouponCodeById(int $couponCodeId): Collection
    {
        $couponCode = CouponCode::find($couponCodeId);

        if ($couponCode === null) throw new ResourceNotFoundException($this->notFoundMessage);

        return $couponCode;
    }


    /**
     * @param array $couponCodePayload
     * 
     * @return CouponCode
     * @throws BadRequestException
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
     * 
     * @return bool
     * @throws ResourceNotFoundException
     */
    public function updateCouponCode(int $couponCodeId, array $couponCodePayload): bool
    {
        $affectedRowsCount = CouponCode::where('id', $couponCodeId)->update($couponCodePayload);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }


    /**
     * @param int $couponCodeId
     * 
     * @return bool
     * @throws ResourceNotFoundException
     */
    public function deleteCouponCodeById(int $couponCodeId): void
    {
        $affectedRowsCount = CouponCode::destroy($couponCodeId);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);
    }
}
