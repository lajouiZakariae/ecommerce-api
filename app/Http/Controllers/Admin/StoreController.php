<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\StoreService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

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
     * Display a spicific store.
     * 
     * @param  int  $storeId
     * 
     * @return Store
     */
    public function show(int $storeId): Store
    {
        return $this->storeService->getStoreById($storeId);
    }

    /**
     * Store a newly created store
     *
     * @return Store
     * 
     */
    public function store(): Store
    {
        request()->merge(['slug' => str(request()->input('name'))->slug()]);

        $validatedStorePayload = request()->validate(
            [
                'name' => ['required', 'min:1', 'max:255'],
                'slug' => ['required', 'min:1', 'max:255', 'unique:stores,slug'],
                'address' => ['nullable', 'min:1', 'max:500'],
            ]
        );

        return $this->storeService->createStore($validatedStorePayload);
    }

    /**
     * Update a specific store
     *
     * @param int $storeId
     * 
     * @return Store
     * 
     */
    public function update(int $storeId): Store
    {
        request()->merge(['slug' => str(request()->input('name'))->slug()]);

        $validatedStorePayload = request()->validate(
            [
                'name' => ['required', 'min:1', 'max:255'],
                'slug' => ['required', 'min:1', 'max:255', 'unique:stores,slug'],
                'address' => ['nullable', 'min:1', 'max:500'],
            ]
        );

        return $this->storeService->updateStore($storeId, $validatedStorePayload);
    }

    /**
     * Delete a specific store
     *
     * @param int $storeId
     * 
     * @return Response
     * 
     */
    public function destroy(int $storeId): Response
    {
        $this->storeService->deleteStoreById($storeId);

        return response()->noContent();
    }
}
