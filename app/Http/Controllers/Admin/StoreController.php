<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class StoreController extends Controller
{
    /**
     * Display a listing of the stores.
     *
     * @return Collection<int,Store>
     */
    public function index(): Collection
    {
        $stores = Store::query();

        $stores = request()->input("sortBy") === "oldest"
            ? $stores->oldest()
            : $stores->latest();

        return $stores->get();
    }

    public function store()
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

        $store = new Store($validatedStorePayload);

        if (!$store->save()) throw new BadRequestException("Store Could not be created");

        return $store;
    }

    public function update(int $store_id): Response
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

        $affectedRowsCount = Store::where('id', $store_id)->update($validatedStorePayload);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException("Store Not Found");

        return response()->noContent();
    }

    public function destroy(int $store_id): Response
    {
        $affectedRowsCount = Store::destroy($store_id);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException("Store Not Found");

        return response()->noContent();
    }
}
