<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Services\ProductService;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Patch;

#[ApiResource('products')]
class ProductController extends Controller
{
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

        return $productService->getAll($filters);
    }

    /**
     * Store a newly created product in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(): Response
    {
        $data = request()->validate([
            'title' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $product = Product::create($data);

        return response(new ProductResource($product), Response::HTTP_CREATED);
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product): Response
    {
        return response(new ProductResource($product));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \App\Http\Requests\Admin\ProductUpdateRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product): Response
    {
        $data = $request->validated();

        $product->update($data);

        return response()->noContent();
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  string  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductService $productService, int $product_id): Response
    {
        $productService->deleteById($product_id);

        return response()->noContent();
    }

    /**
     * Publish the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    #[Patch('/products/{product}/toggle-publish')]
    public function publish(Product $product): Response
    {
        $product->published = !$product->published;
        $product->save();

        return response()->noContent();
    }

    /**
     * Display a listing of products for a specific category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    #[Get('/categories/{category}/products')]
    public function categoryProducts(Category $category)
    {
        return ProductResource::collection($category->products)->withoutWrapping();
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
