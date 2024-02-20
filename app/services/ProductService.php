<?php

namespace App\Services;

use App\Models\Product;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
    private function queryFilters(array $filters): Builder
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
    public function getAll(array $filters): LengthAwarePaginator
    {
        $products = $this
            ->queryFilters($filters)
            ->with([
                'thumbnail',
            ])
            ->withSum('inventory', 'quantity')
            ->paginate(10);

        return $products;
    }

    /**
     * Get a product by its ID.
     *
     * @param int $id The ID of the product.
     *
     * @return Product|null The product instance if found, otherwise null.
     */
    public function getById(int $id): Product | null
    {
        $product = Product::query()
            ->withAggregate('inventory AS quantity', 'quantity', 'sum')
            ->find($id);

        return $product;
    }

    /**
     * Get a product by its ID.
     *
     * @param int $id The ID of the product.
     *
     * @return Product|null The product instance if found, otherwise null.
     */
    public function getByCategory(int $caetgory_id): LengthAwarePaginator
    {
        return Product::where('category_id', $caetgory_id)->paginate();
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

        $product->save();

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

        return $affectedRowsCount !== 0;
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

        return $affectedRowsCount !== 0;
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

        return $affectedRowsCount !== 0;
    }
}
