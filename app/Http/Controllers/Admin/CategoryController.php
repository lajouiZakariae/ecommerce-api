<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {
    }

    /**
     * Display a listing of the categories.
     *
     * @return Collection<int,Category>
     */
    public function index(): Collection
    {
        return $this->categoryService->getAllCategories([
            'sortBy' => request()->input('sortBy', 'oldest')
        ]);
    }

    public function store(): Category
    {
        $categoryPayload = [
            'name' => request()->input('name'),
            'slug' => str(request()->input('name'))->slug(),
            'description' => request()->input('description'),
        ];

        $categoryValidator = validator()->make($categoryPayload, [
            'name' => ['required', 'min:1', 'max:255'],
            'slug' => ['required', 'min:1', 'max:255', 'unique:categories,slug'],
            'description' => ['nullable', 'min:1', 'max:500'],
        ]);

        $validatedCategoryPayload = $categoryValidator->validate();

        return $this->categoryService->createCategory($validatedCategoryPayload);
    }

    public function update(int $categoryId): Category
    {
        $categoryPayload = [
            'name' => request()->input('name'),
            'slug' => str(request()->input('name'))->slug(),
            'description' => request()->input('description'),
        ];

        $categoryValidator = validator()->make($categoryPayload, [
            'name' => ['required', 'min:1', 'max:255'],
            'slug' => ['required', 'min:1', 'max:255', 'unique:categories,slug'],
            'description' => ['nullable', 'min:1', 'max:500'],
        ]);

        $validatedCategoryPayload = $categoryValidator->validate();

        $updatedCategory = $this->categoryService->updateCategory($categoryId, $validatedCategoryPayload);

        return $updatedCategory;
    }

    public function destroy(int $categoryId): Response
    {
        $this->categoryService->deleteCatgoryById($categoryId);

        return response()->noContent();
    }
}
