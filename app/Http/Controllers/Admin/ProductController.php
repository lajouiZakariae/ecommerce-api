<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SortBy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Validator;


class ProductController extends Controller
{

    public function __construct(private ProductService $productService)
    {
    }

    /**
     * Get valid filters only
     *
     * @param array<string,mixed> $filters
     * 
     * @return array<string,mixed>
     * 
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
     * Get a listing of the products.
     *
     * @return ResourceCollection
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
     * Store a newly created product.
     * 
     * @param ProductStoreRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request): Response
    {
        $this->authorize('create', Product::class);

        $data = $request->validated();

        $product = $this->productService->createProduct($data);

        return response(ProductResource::make($product), Response::HTTP_CREATED);
    }

    /**
     * Get a specific product.
     *
     * @param  int $productId
     * 
     * @return ProductResource
     */
    public function show($productId): ProductResource
    {
        $product = $this->productService->getProductById($productId);

        return ProductResource::make($product);
    }

    /**
     * Update a specific product in storage.
     * 
     * @param ProductUpdateRequest $request
     * @param mixed $productId
     * 
     * @return ProductResource
     */
    public function update(ProductUpdateRequest $request,  $productId): ProductResource
    {
        $data = $request->validated();

        $updatedProduct = $this->productService->updateProduct($productId, $data);

        return ProductResource::make($updatedProduct);
    }

    /**
     * Delete a specific product from storage.
     *
     * @param  int  $productId
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $productId): Response
    {
        $this->authorize('delete', Product::class);

        $this->productService->deleteProductById($productId);

        return response()->noContent();
    }

    /**
     * Toggle publish state of a specific product.
     *
     * @param  \App\Services\ProductService  $productService
     * @param  int  $productId
     * 
     * @return \Illuminate\Http\Response
     */
    public function togglePublish(int $productId): Response
    {
        $this->authorize('update', Product::class);

        $this->productService->togglePublishedState($productId);

        return response()->noContent();
    }

    /**
     * Get a listing of products for a specific category.
     *
     * @param  int  $categoryId
     * 
     * @return ResourceCollection
     */
    public function productsByCategory($categoryId): ResourceCollection
    {
        if (!Category::where('id', $categoryId)->exists()) throw new ResourceNotFoundException("Category Not Found");

        return ProductResource::collection(
            $this->productService->getProductsByCategory($categoryId)
        );
    }

    /**
     * Get a listing of products for a specific Store.
     *
     * @param  int  $storeId
     * 
     * @return ResourceCollection
     */
    public function productsByStore($storeId): ResourceCollection
    {
        return ProductResource::collection(
            $this->productService->getProductsByStore($storeId)
        );
    }
}
