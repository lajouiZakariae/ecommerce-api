<?php

namespace App\Services;

use App\Enums\SortBy;
use App\Models\Product;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
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

    public  function getById(int $id): Product | null
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        $product = new Product($data);

        $product->save();

        return $product;
    }

    public  function deleteById(int $id): bool
    {
        $affectedRowscount = Product::where('id', $id)->delete();

        return $affectedRowscount !== 0;
    }

    public  function togglePublishedState(int $id): bool
    {
        $affectedRowscount = DB::update("UPDATE products SET published = !published WHERE id = :id ;", [
            ':id' => $id
        ]);

        return $affectedRowscount !== 0;
    }
}
