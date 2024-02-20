<?php

namespace App\Services;

use App\Enums\SortBy;
use App\Models\Product;
use Egulias\EmailValidator\Result\Reason\UnclosedComment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Validator;

class ProductService
{
    private function validProductFilters(array $filters): array
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

    private function queryFilters($filters): Builder
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

    public function getAll($filters)
    {
        $validFilters = $this->validProductFilters($filters);

        $products = $this
            ->queryFilters($validFilters)
            ->with([
                'thumbnail',
            ])
            ->withSum('inventory', 'quantity')
            ->paginate(10);

        return ($products);
    }

    public  function deleteById(int $id): bool
    {
        return Product::destroy($id);
    }
}
