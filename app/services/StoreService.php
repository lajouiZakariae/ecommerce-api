<?php

namespace App\Services;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class StoreService
{
    private $notFoundMessage = "Store Not Found";

    /**
     * @return Collection
     */
    public function getAllStores(): Collection
    {
        $stores = Store::query();

        $stores = request()->input("sortBy") === "oldest"
            ? $stores->oldest()
            : $stores->latest();

        return $stores->get();
    }

    /**
     * @return Collection
     */
    public function getStoreById(int $storeId): Collection
    {
        $store = Store::find($storeId);

        if ($store === null) throw new ResourceNotFoundException($this->notFoundMessage);

        return $store;
    }

    /**
     * @param array $storePayload
     * @return Store
     */
    public function createStore(array $storePayload): Store
    {
        $store = new Store($storePayload);

        if (!$store->save()) throw new BadRequestException("Store Could not be created");

        return $store;
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function deleteStoreById(int $storeId): bool
    {
        $affectedRowsCount = Store::destroy($storeId);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }
}
