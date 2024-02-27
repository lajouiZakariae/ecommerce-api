<?php

namespace App\Services;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\Product;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ProductService
{
    private $notFoundMessage = "Product Not Found";

    /**
     * Apply filters to the product query.
     *
     * @param array $productFilters
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    private function filterAndReturnOnlyValidProductModelQueryFilters(array $productFilters): Builder
    {
        return Product::query()
            ->when(
                isset($productFilters['price_from']),
                fn (Builder $query) => $query->where('price', '>=', $productFilters['price_from'])
            )
            ->when(
                isset($productFilters['price_to']),
                fn (Builder $query) => $query->where('price', '<=', $productFilters['price_to'])
            )
            ->when(
                isset($productFilters['cost_from']),
                fn (Builder $query) => $query->where('cost', '>=', $productFilters['cost_from'])
            )
            ->when(
                isset($productFilters['cost_to']),
                fn (Builder $query) => $query->where('cost', '<=', $productFilters['cost_to'])
            )
            ->when(
                isset($productFilters['sort_by']),
                fn (Builder $query) => $query->orderBy($productFilters['sort_by'], $productFilters['order'] ?? 'asc')
            );
    }

    /**
     * @param array $productFilters
     * @return LengthAwarePaginator The paginated result set of products.
     */
    public function getAllProductsMatchFilters(array $productFilters): LengthAwarePaginator
    {
        $products = $this
            ->filterAndReturnOnlyValidProductModelQueryFilters($productFilters)
            ->with(['thumbnail'])
            ->withSum('inventory AS quantity', 'quantity')
            ->paginate(10);

        return $products;
    }


    /**
     * @param int $productId
     * @return Product
     * @throws Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function getProductById(int $productId): Product
    {
        $product = Product::query()
            ->with('thumbnail')
            ->withSum('inventory AS quantity', 'quantity')
            ->find($productId);

        if ($product === null)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return $product;
    }

    /**
     * @param array $productPayload
     *
     * @return Product The created product instance.
     */
    public function createProduct(array $productPayload): Product
    {
        $product = new Product($productPayload);

        $saved = $product->save();

        if (!$saved) throw new BadRequestException("Product Could not be Created");

        return $product;
    }

    /**
     * @param int $productId
     * 
     * @param array $productPayload
     *
     * @return Product
     */
    public function updateProduct(int $productId, array $productPayload): Product
    {
        $affectedRowsCount = Product::where('id', $productId)->update($productPayload);

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return Product::find($productId);
    }

    /**
     * @param int $productId
     *
     * @return bool 
     */
    public function deleteProductById(int $productId): bool
    {
        $affectedRowsCount = Product::where('id', $productId)->delete();

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * Toggle the published state of a product by its ID.
     *
     * @param int $productId
     *
     * @return bool True if the state toggle is successful, otherwise false.
     */
    public function togglePublishedState(int $productId): bool
    {
        $affectedRowsCount = DB::update("UPDATE products SET published = !published WHERE id = :id ;", [
            ':id' => $productId
        ]);

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * @param int $categoryId
     *
     * @return LengthAwarePaginator.
     */
    public function getProductsByCategory(int $categoryId): LengthAwarePaginator
    {
        return Product::where('category_id', $categoryId)->paginate();
    }

    /**
     * @param int $storeId 
     *
     * @return LengthAwarePaginator
     */
    public function getProductsByStore(int $storeId): LengthAwarePaginator
    {
        return Product::where('store_id', $storeId)->paginate();
    }
}
