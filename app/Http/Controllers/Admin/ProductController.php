<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SortBy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Store;
use App\Services\ProductService;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Spatie\RouteAttributes\Attributes\Get;
use Validator;

// #[ApiResource('products')]
class ProductController extends Controller
{
    /**
     * Get valid filters only
     */
    private function getValidFiltersOnly(array $filters): array
    {
        return Validator::make(
            $filters,
            [
                'price_from' => ['numeric'],
                'price_to' => ['numeric'],
                'cost_from' => ['numeric'],
                'cost_to' => ['numeric'],
                'sort_by' => [Rule::enum(SortBy::class)],
                'order' => [Rule::in(['asc', 'desc'])],
            ]
        )->valid();
    }

    /**
     * Display a listing of products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductService $productService)
    {
        $filters = [
            'price_from' => request()->input('price_from'),
            'price_to' => request()->input('price_to'),
            'cost_from' => request()->input('cost_from'),
            'cost_to' => request()->input('cost_to'),
            'sort_by' => request()->input('sort_by'),
            'order' => request()->input('order'),
        ];

        $validFilters = $this->getValidFiltersOnly($filters);

        return ProductResource::collection($productService->getAll($validFilters));
    }

    /**
     * Store a newly created product in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request, ProductService $productService)
    {
        $data = $request->validated();

        $product = $productService->create($data);

        return response(new ProductResource($product), Response::HTTP_CREATED);
    }

    /**
     * Display the specified product.
     *
     * @param  int $product_id
     * @return \Illuminate\Http\Response
     */
    public function show(ProductService $productService, $product_id): ProductResource
    {
        $product = $productService->getById($product_id);

        abort_if(!$product, 404);

        return new ProductResource($product);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \App\Http\Requests\Admin\ProductUpdateRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, ProductService $productService, $product_id): Response
    {
        $data = $request->validated();

        $productService->update($product_id, $data);

        return response()->noContent();
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Services\ProductService  $productService
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductService $productService, int $product_id): Response
    {
        $deleted = $productService->deleteById($product_id);

        abort_if(!$deleted, 404);

        return response()->noContent();
    }

    /**
     * Toggle publish state of the specified product.
     *
     * @param  \App\Services\ProductService  $productService
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function togglePublish(ProductService $productService, int $product_id)
    {
        $toggled = $productService->togglePublishedState($product_id);

        abort_if(!$toggled, 404);

        return response()->noContent();
    }

    /**
     * Display a listing of products for a specific category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function categoryProducts(ProductService $productService, $category_id)
    {
        return $productService->getByCategory($category_id);
    }

    /**
     * Display a listing of products for a specific store.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    #[Get('/stores/{store}/products')]
    public function storeProducts(Store $store)
    {
        return ProductResource::collection($store->products)->withoutWrapping();
    }
}
