<?php

namespace App\Services;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CategoryService
{
    private $notFoundMessage = "Category Not Found";

    /**
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        $categories = Category::query();

        $categories = request()->input("sortBy") === "oldest"
            ? $categories->oldest()
            : $categories->latest();

        return $categories->get();
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
     * 
     */
    public function updateStore(int $storeId, $storePayload): bool
    {
        $affectedRowsCount = Store::where('id', $storeId)->update($storePayload);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException("Store Not Found");

        return true;
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
