<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PurchaseItemResource;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\ScopeBindings;

/**
 * @group Purchase Items
 * @authenticated
 */
#[Prefix('purchases/{purchase}')]
#[ApiResource('purchase-items', except: 'show')]
class PurchaseItemController extends Controller
{
    /**
     * Display a listing of purchase items for a specific purchase.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    #[Get('purchase-items', name: 'purchase-items.index')]
    #[ScopeBindings]
    public function index(Purchase $purchase): Response
    {
        $purchaseItems = $purchase->purchaseItems;

        return response(PurchaseItemResource::collection($purchaseItems));
    }

    /**
     * Store a newly created purchase item in storage for a specific purchase.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function store(Purchase $purchase): Response
    {
        $data = request()->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer'],
        ]);

        $purchaseItem = new PurchaseItem($data);

        $purchase->purchaseItems()->save($purchaseItem);

        return response('', Response::HTTP_CREATED);
    }

    /**
     * Display the specified purchase item.
     *
     * @param  \App\Models\Purchase  $purchase
     * @param  \App\Models\PurchaseItem  $purchaseItem
     * @return \Illuminate\Http\Response
     */
    #[Get('purchase-items/{purchase_item}', name: 'purchase-items.show')]
    #[ScopeBindings]
    public function show(Purchase $purchase, PurchaseItem $purchaseItem): Response
    {
        return response(new PurchaseItemResource($purchaseItem));
    }

    /**
     * Update the specified purchase item in storage for a specific purchase.
     *
     * @param  mixed  $purchase
     * @param  \App\Models\PurchaseItem  $purchaseItem
     * @return \Illuminate\Http\Response
     */
    public function update($purchase, PurchaseItem $purchaseItem): Response
    {
        $data = request()->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer'],
        ]);

        $purchaseItem->update($data);

        return response()->noContent();
    }

    /**
     * Remove the specified purchase item from storage for a specific purchase.
     *
     * @param  mixed  $purchase
     * @param  \App\Models\PurchaseItem  $purchaseItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($purchase, PurchaseItem $purchaseItem): Response
    {
        $purchaseItem->delete();

        return response()->noContent();
    }

    /**
     * Increment the quantity of the specified purchase item for a specific purchase.
     *
     * @param  mixed  $purchase
     * @param  \App\Models\PurchaseItem  $purchaseItem
     * @return \Illuminate\Http\Response
     */
    #[Patch('purchase-items/{purchase_item}/increment-quantity', 'purchase-items.increment-quantity')]
    public function incrementQuantity($purchaseId, PurchaseItem $purchaseItem): Response
    {
        if ($purchaseItem->quantity === 18_446_744_073_709_551_615) {
            return response(['error' => 'maximum value reached'], Response::HTTP_BAD_REQUEST);
        }

        $purchaseItem->query()->increment('quantity');

        return response()->noContent();
    }

    /**
     * Decrement the quantity of the specified purchase item for a specific purchase.
     *
     * @param  mixed  $purchase
     * @param  \App\Models\PurchaseItem  $purchaseItem
     * @return \Illuminate\Http\Response
     */
    #[Patch('purchase-items/{purchase_item}/decrement-quantity', 'purchase-items.decrement-quantity')]
    public function decrementQuantity($purchaseId, PurchaseItem $purchaseItem): Response
    {
        if ($purchaseItem->quantity === 0) {
            return response(['error' => 'minimum value reached'], Response::HTTP_BAD_REQUEST);
        }

        $purchaseItem->query()->decrement('quantity');

        return response()->noContent();
    }
}
