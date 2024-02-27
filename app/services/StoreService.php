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
    public function getAllStores(array $filters): Collection
    {
        $stores = Store::query();

        $stores = $filters['sortBy'] === "oldest"
            ? $stores->oldest()
            : $stores->latest();

        return $stores->get();
    }

    /**
     * @param int $storeId
     * 
     * @return Store
     * @throws ResourceNotFoundException
     */
    public function getStoreById(int $storeId): Store
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
     * @param array $storePayload 
     * 
     * @return bool
     * @throws ResourceNotFoundException
     */
    public function updateStore(int $storeId, array $storePayload): bool
    {
        $affectedRowsCount = Store::where('id', $storeId)->update($storePayload);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * @param int $storeId
     * 
     * @return bool
     * @throws ResourceNotFoundException
     */
    public function deleteStoreById(int $storeId): bool
    {
        $affectedRowsCount = Store::destroy($storeId);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }
}
