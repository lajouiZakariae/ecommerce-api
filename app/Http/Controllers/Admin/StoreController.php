<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\StoreService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class StoreController extends Controller
{

    public function __construct(private StoreService $storeService)
    {
    }

    /**
     * Display a listing of the stores.
     *
     * @return Collection<int,Store>
     */
    public function index(): Collection
    {
        return $this->storeService->getAllStores([
            "sortBy" => request()->input("sortBy")
        ]);
    }

    /**
     * @return Store
     */
    public function show(int $storeId): Store
    {
        return $this->storeService->getStoreById($storeId);
    }

    public function store(): Store
    {
        $storePayload = [
            'name' => request()->input('name'),
            'slug' => str(request()->input('name'))->slug(),
            'address' => request()->input('address'),
        ];

        $storeValidator = validator()->make($storePayload, [
            'name' => ['required', 'min:1', 'max:255'],
            'slug' => ['required', 'min:1', 'max:255', 'unique:stores,slug'],
            'address' => ['nullable', 'min:1', 'max:500'],
        ]);

        $validatedStorePayload = $storeValidator->validate();

        return $this->storeService->createStore($validatedStorePayload);
    }

    public function update(int $storeId): Store
    {
        $storePayload = [
            'name' => request()->input('name'),
            'slug' => str(request()->input('name'))->slug(),
            'address' => request()->input('address'),
        ];

        $storeValidator = validator()->make($storePayload, [
            'name' => ['required', 'min:1', 'max:255'],
            'slug' => ['required', 'min:1', 'max:255', 'unique:stores,slug'],
            'address' => ['nullable', 'min:1', 'max:500'],
        ]);

        $validatedStorePayload = $storeValidator->validate();

        $updatedStore = $this->storeService->updateStore($storeId, $validatedStorePayload);

        return $updatedStore;
    }

    public function destroy(int $storeId): Response
    {
        $this->storeService->deleteStoreById($storeId);

        return response()->noContent();
    }
}
