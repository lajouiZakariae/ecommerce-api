<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SortBy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Validator;

class ProductController extends Controller
{

    public function __construct(private ProductService $productService)
    {
    }

    /**
     * Get valid filters only
     */
    private function getValidProductFilters(array $filters): array
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
    public function index()
    {
        $productFilters = [
            'price_from' => request()->input('price_from'),
            'price_to' => request()->input('price_to'),
            'cost_from' => request()->input('cost_from'),
            'cost_to' => request()->input('cost_to'),
            'sort_by' => request()->input('sort_by'),
            'order' => request()->input('order'),
        ];

        $validProductFilters = $this->getValidProductFilters($productFilters);

        return ProductResource::collection($this->productService->getAllProductsMatchFilters($validProductFilters));
    }

    /**
     * Store a newly created product in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        $data = $request->validated();

        $product = $this->productService->create($data);

        return response(new ProductResource($product), Response::HTTP_CREATED);
    }

    /**
     * Display the specified product.
     *
     * @param  int $product_id
     * @return \Illuminate\Http\Response
     */
    public function show($product_id): ProductResource
    {
        $product = $this->productService->getById($product_id);

        return new ProductResource($product);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \App\Http\Requests\Admin\ProductUpdateRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request,  $product_id): Response
    {
        $data = $request->validated();

        $this->productService->update($product_id, $data);

        return response()->noContent();
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Services\ProductService  $productService
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $product_id): Response
    {
        $this->productService->deleteById($product_id);

        return response()->noContent();
    }

    /**
     * Toggle publish state of the specified product.
     *
     * @param  \App\Services\ProductService  $productService
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function togglePublish(int $product_id)
    {
        $this->productService->togglePublishedState($product_id);

        return response()->noContent();
    }

    /**
     * Display a listing of products for a specific category.
     *
     * @param  int  $category_id
     * @return \Illuminate\Http\Response
     */
    public function productsByCategory($category_id)
    {
        return $this->productService->getByCategory($category_id);
    }

    /**
     * Display a listing of products for a specific Store.
     *
     * @param  int  $store_id
     * @return \Illuminate\Http\Response
     */
    public function productsByStore($category_id)
    {
        return $this->productService->getByCategory($category_id);
    }
}
