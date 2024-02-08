<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SupplierStoreRequest;
use App\Http\Requests\Admin\SupplierUpdateRequest;
use App\Http\Resources\Admin\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;

/**
 * @group Suppliers
 */
#[ApiResource('suppliers')]
class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $suppliers = Supplier::all();

        return response(SupplierResource::collection($suppliers));
    }

    /**
     * Store a newly created supplier in storage.
     *
     * @param  \App\Http\Requests\Admin\SupplierStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierStoreRequest $request): Response
    {
        $supplier = Supplier::create($request->validated());

        return response('', Response::HTTP_CREATED);
    }

    /**
     * Display the specified supplier.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier): Response
    {
        return response(new SupplierResource($supplier));
    }

    /**
     * Update the specified supplier in storage.
     *
     * @param  \App\Http\Requests\Admin\SupplierUpdateRequest  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierUpdateRequest $request, Supplier $supplier): Response
    {
        $supplier->update($request->validated());

        return response()->noContent();
    }

    /**
     * Remove the specified supplier from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier): Response
    {
        $supplier->delete();

        return response()->noContent();
    }
}
