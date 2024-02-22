<?php

namespace App\Services;

use App\Exceptions\ResourceNotCreatedException;
use App\Models\Product;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class ProductService
 *
 * This class provides functionality for managing products.
 *
 * @package App\Services
 */
class ProductService
{
    /**
     * Apply filters to the product query.
     *
     * @param array $filters
     *
     * @return Builder The Eloquent query builder instance.
     */
    private function filterAndReturnOnlyValidProductModelQueryFilters(array $filters): Builder
    {
        return Product::query()
            ->when(
                isset($filters['price_from']),
                fn (Builder $query) => $query->where('price', '>=', $filters['price_from'])
            )
            ->when(
                isset($filters['price_to']),
                fn (Builder $query) => $query->where('price', '<=', $filters['price_to'])
            )
            ->when(
                isset($filters['cost_from']),
                fn (Builder $query) => $query->where('cost', '>=', $filters['cost_from'])
            )
            ->when(
                isset($filters['cost_to']),
                fn (Builder $query) => $query->where('cost', '<=', $filters['cost_to'])
            )
            ->when(
                isset($filters['sort_by']),
                fn (Builder $query) => $query->orderBy($filters['sort_by'], $filters['order'] ?? 'asc')
            );
    }

    /**
     * Get a paginated list of products based on specified filters.
     *
     * @param array $filters
     * @return LengthAwarePaginator The paginated result set of products.
     */
    public function getAllProductsMatchFilters(array $filters): LengthAwarePaginator
    {
        $products = $this
            ->filterAndReturnOnlyValidProductModelQueryFilters($filters)
            ->with(['thumbnail'])
            ->withSum('inventory AS quantity', 'quantity')
            ->paginate(10);

        return $products;
    }


    /**
     * Get a product by its ID or throw a ResourceNotFound Exception
     *
     * @param int $id The ID of the product.
     * @return Product The product instance.
     * @throws Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function getById(int $id): Product
    {
        $product = Product::query()
            ->with('thumbnail')
            ->withSum('inventory AS quantity', 'quantity')
            ->find($id);

        if ($product === null)
            throw new ResourceNotFoundException("Product Not Found !!");

        return $product;
    }

    /**
     * Create a new product.
     *
     * @param array $data An associative array of data for creating the product.
     *
     * @return Product The created product instance.
     */
    public function create(array $data): Product
    {
        $product = new Product($data);

        $saved = $product->save();

        if (!$saved) throw new ResourceNotCreatedException("Product Could not be Created");

        return $product;
    }

    /**
     * Update a product by its ID.
     *
     * @param int $id The ID of the product to be updated.
     * 
     * @param array $data
     *
     * @return bool True if the update was successful, otherwise false.
     */
    public function update(int $id, array $data): bool
    {
        $affectedRowsCount = Product::where('id', $id)->update($data);

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException('Product Not Found!!');

        return true;
    }

    /**
     * Delete a product by its ID.
     *
     * @param int $id The ID of the product to be deleted.
     *
     * @return bool True if the deletion is successful, otherwise false.
     */
    public function deleteById(int $id): bool
    {
        $affectedRowsCount = Product::where('id', $id)->delete();

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException('Product Not Found!!');

        return true;
    }

    /**
     * Toggle the published state of a product by its ID.
     *
     * @param int $id The ID of the product to toggle the published state.
     *
     * @return bool True if the state toggle is successful, otherwise false.
     */
    public function togglePublishedState(int $id): bool
    {
        $affectedRowsCount = DB::update("UPDATE products SET published = !published WHERE id = :id ;", [
            ':id' => $id
        ]);

        if ($affectedRowsCount === 0) {
            throw new ResourceNotFoundException('Product Not Found!!');
        };

        return true;
    }

    /**
     * Get stores by store.
     *
     * @param int $store_id The ID of the store.
     *
     * @return LengthAwarePaginator.
     */
    public function getByCategory(int $caetgory_id): LengthAwarePaginator
    {
        return Product::where('category_id', $caetgory_id)->paginate();
    }

    /**
     * Get products by store.
     *
     * @param int $store_id The ID of the store.
     *
     * @return LengthAwarePaginator
     */
    public function getByStore(int $store_id): LengthAwarePaginator
    {
        return Product::where('category_id', $store_id)->paginate();
    }
}
