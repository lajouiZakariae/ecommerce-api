<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PurchaseStoreRequest;
use App\Http\Requests\Admin\PurchaseUpdateRequest;
use App\Http\Resources\Admin\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;

/**
 * @group Purchases
 */
#[ApiResource('purchases')]
class PurchaseController extends Controller
{
    /**
     * Display a listing of the purchases.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $purchases = Purchase::query()
            ->with(['supplier:id,name', 'paymentMethod:id,name'])
            ->withCount('purchaseItems')
            ->get();

        return response(PurchaseResource::collection($purchases));
    }

    /**
     * Store a newly created purchase in storage.
     *
     * @param  \App\Http\Requests\Admin\PurchaseStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseStoreRequest $request): Response
    {
        $data = $request->validated();

        $purchase = Purchase::create($data);

        return response($purchase, Response::HTTP_CREATED);
    }

    /**
     * Display the specified purchase.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase): Response
    {
        $purchaseData = $purchase->load(['supplier:id,name', 'store:id,name', 'paymentMethod:id,name']);

        return response(new PurchaseResource($purchaseData));
    }

    /**
     * Update the specified purchase in storage.
     *
     * @param  \App\Http\Requests\Admin\PurchaseUpdateRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(PurchaseUpdateRequest $request, Purchase $purchase): Response
    {
        $data = $request->validated();

        $purchase->update($data);

        return response()->noContent();
    }

    /**
     * Remove the specified purchase from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase): Response
    {
        $purchase->delete();

        return response()->noContent();
    }
}
