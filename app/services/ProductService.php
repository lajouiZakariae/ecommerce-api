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
     * @return Builder The Eloquent query builder instance.
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
     * @param int $product_id
     * @return Product
     * @throws Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function getProductById(int $product_id): Product
    {
        $product = Product::query()
            ->with('thumbnail')
            ->withSum('inventory AS quantity', 'quantity')
            ->find($product_id);

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
     * @param int $product_id
     * 
     * @param array $productPayload
     *
     * @return bool True if the update was successful, otherwise false.
     */
    public function updateProduct(int $product_id, array $productPayload): bool
    {
        $affectedRowsCount = Product::where('id', $product_id)->update($productPayload);

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * @param int $product_id
     *
     * @return bool 
     */
    public function deleteProductById(int $product_id): bool
    {
        $affectedRowsCount = Product::where('id', $product_id)->delete();

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * Toggle the published state of a product by its ID.
     *
     * @param int $product_id
     *
     * @return bool True if the state toggle is successful, otherwise false.
     */
    public function togglePublishedState(int $product_id): bool
    {
        $affectedRowsCount = DB::update("UPDATE products SET published = !published WHERE id = :id ;", [
            ':id' => $product_id
        ]);

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * @param int $category_id
     *
     * @return LengthAwarePaginator.
     */
    public function getProductsByCategory(int $category_id): LengthAwarePaginator
    {
        return Product::where('category_id', $category_id)->paginate();
    }

    /**
     * @param int $store_id 
     *
     * @return LengthAwarePaginator
     */
    public function getProductsByStore(int $store_id): LengthAwarePaginator
    {
        return Product::where('category_id', $store_id)->paginate();
    }
}
