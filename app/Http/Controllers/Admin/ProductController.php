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
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\Response as AttributesResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Validator;

#[Info(version: 1, title: 'Ecommerce Api')]
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
     * @return \Illuminate\Http\Response
     */
    #[Get(
        path: '/v1/products',
        summary: 'display a listing of products',
        responses: [new AttributesResponse(response: 200, description: 'Product listing returned')],
    )]
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
        $this->authorize('create', Product::class);

        $data = $request->validated();

        $product = $this->productService->createProduct($data);

        return response(ProductResource::make($product), Response::HTTP_CREATED);
    }

    /**
     * Display the specified product.
     *
     * @param  int $productId
     * @return \Illuminate\Http\Response
     */
    #[Get(
        path: '/v1/products/{product}',
        summary: 'display a single of product',
        responses: [
            new AttributesResponse(response: 200, description: 'Product Returned'),
            new AttributesResponse(response: 404, description: 'Product Not Found'),
        ],
    )]
    public function show($productId): ProductResource
    {
        $product = $this->productService->getProductById($productId);

        return ProductResource::make($product);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \App\Http\Requests\Admin\ProductUpdateRequest  $request
     * @param  \App\Models\Product  $product
     * @return Product
     */
    public function update(ProductUpdateRequest $request,  $productId): Product
    {
        $data = $request->validated();

        $updatedProduct = $this->productService->updateProduct($productId, $data);

        return $updatedProduct;
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Services\ProductService  $productService
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $productId): Response
    {
        $this->authorize('delete', Product::class);

        $this->productService->deleteProductById($productId);

        return response()->noContent();
    }

    /**
     * Toggle publish state of the specified product.
     *
     * @param  \App\Services\ProductService  $productService
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function togglePublish(int $productId)
    {
        $this->authorize('update', Product::class);

        $this->productService->togglePublishedState($productId);

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
        if (!Category::where('id', $category_id)->exists()) throw new ResourceNotFoundException("Category Not Found");

        return ProductResource::collection(
            $this->productService->getProductsByCategory($category_id)
        );
    }

    /**
     * Display a listing of products for a specific Store.
     *
     * @param  int  $store_id
     * @return \Illuminate\Http\Response
     */
    public function productsByStore($store_id)
    {
        return ProductResource::collection(
            $this->productService->getProductsByStore($store_id)
        );
    }
}
