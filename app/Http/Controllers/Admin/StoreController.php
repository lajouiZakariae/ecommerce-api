<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStoreRequest;
use App\Http\Requests\Admin\StoreUpdateRequest;
use App\Http\Resources\Admin\StoreResource;
use App\Models\Store;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;

/**
 * @group Stores
 */
#[ApiResource('stores')]
class StoreController extends Controller
{
    /**
     * Display a listing of stores.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $stores = Store::query();

        $stores = request()->input('sortBy') === 'oldest'
            ? $stores->oldest()
            : $stores->latest();

        return response()->make(StoreResource::collection($stores->get()));
    }

    /**
     * Display the specified store.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store): Response
    {
        return response()->make(new StoreResource($store));
    }

    /**
     * Store a newly created store in storage.
     *
     * @param  \App\Http\Requests\Admin\StoreStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStoreRequest $request): Response
    {
        $store = Store::create($request->validated());

        return response()->make($store, Response::HTTP_CREATED);
    }

    /**
     * Update the specified store in storage.
     *
     * @param  \App\Http\Requests\Admin\StoreUpdateRequest  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateRequest $request, Store $store): Response
    {
        $store->update($request->validated());

        return response()->noContent();
    }

    /**
     * Remove the specified store from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store): Response
    {
        $store->delete();

        return response()->noContent();
    }
}
