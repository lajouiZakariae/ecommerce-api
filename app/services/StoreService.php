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
     * @param  mixed $filters
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
     * 
     * @return Store
     * @throws BadRequestException
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
     * @return Store
     * @throws ResourceNotFoundException
     */
    public function updateStore(int $storeId, array $storePayload): Store
    {
        $affectedRowsCount = Store::where('id', $storeId)->update($storePayload);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return Store::find($storeId);
    }

    /**
     * @param int $storeId
     * 
     * @return void
     * @throws ResourceNotFoundException
     */
    public function deleteStoreById(int $storeId): void
    {
        $affectedRowsCount = Store::destroy($storeId);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);
    }
}
