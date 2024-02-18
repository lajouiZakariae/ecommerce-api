<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientStoreRequest;
use App\Http\Requests\Admin\ClientUpdateRequest;
use App\Http\Resources\Admin\ClientResource;
use App\Models\Client;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;

#[ApiResource('clients')]
class ClientController extends Controller
{
    // private function validProductFilters(array $filters): array
    // {
    //     return Validator::make(
    //         $filters,
    //         [
    //             'price_from' => ['numeric'],
    //             'price_to' => ['numeric'],
    //             'cost_from' => ['numeric'],
    //             'cost_to' => ['numeric'],
    //             'sort_by' => [Rule::enum(SortBy::class)],
    //             'order' => [Rule::in(['asc', 'desc'])],
    //         ]
    //     )->valid();
    // }

    // private function filters($filters): Builder
    // {
    //     return Product::query()
    //         ->when(
    //             isset($filters['price_from']),
    //             fn (Builder $query) => $query->where('price', '>=', $filters['price_from'])
    //         )
    //         ->when(
    //             isset($filters['price_to']),
    //             fn (Builder $query) => $query->where('price', '<=', $filters['price_to'])
    //         )
    //         ->when(
    //             isset($filters['cost_from']),
    //             fn (Builder $query) => $query->where('cost', '>=', $filters['cost_from'])
    //         )
    //         ->when(
    //             isset($filters['cost_to']),
    //             fn (Builder $query) => $query->where('cost', '<=', $filters['cost_to'])
    //         )
    //         ->when(
    //             isset($filters['sort_by']),
    //             fn (Builder $query) => $query->orderBy($filters['sort_by'], $filters['order'] ?? 'asc')
    //         );
    // }

    /**
     * Display a listing of products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::query()->paginate(10);

        return ClientResource::collection($clients);
    }

    /**
     * Store a newly created product in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ClientStoreRequest $request): Response
    {
        $data = $request->validated();

        $client = Client::create($data);

        return response($client, Response::HTTP_CREATED);
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client): Response
    {
        return response($client);
    }

    /**
     * Update the specified client in storage.
     *
     * @param  \App\Http\Requests\Admin\ClientUpdateRequest  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(ClientUpdateRequest $request, Client $client): Response
    {
        $data = $request->validated();

        $client->update($data);

        return response()->noContent();
    }

    /**
     * Remove the specified client from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client): Response
    {
        $client->delete();

        return response()->noContent();
    }

    /**
     * Publish the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    // #[Patch('/clients/{product}/toggle-publish')]
    // public function publish(Product $product): Response
    // {
    //     $product->published = !$product->published;
    //     $product->save();

    //     return response()->noContent();
    // }

    /**
     * Display a listing of products for a specific category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    // #[Get('/categories/{category}/products')]
    // public function categoryProducts(Category $category)
    // {
    //     return ProductResource::collection($category->products)->withoutWrapping();
    // }

    /**
     * Display a listing of products for a specific store.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    // #[Get('/stores/{store}/products')]
    // public function storeProducts(Store $store)
    // {
    //     return ProductResource::collection($store->products)->withoutWrapping();
    // }
}
