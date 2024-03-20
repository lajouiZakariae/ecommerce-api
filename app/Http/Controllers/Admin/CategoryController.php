<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService
    ) {
    }

    /**
     * Get a listing of the categories.
     *
     * @return ResourceCollection<CategoryResource>
     */
    public function index(): ResourceCollection
    {
        $categories = $this->categoryService->getAllCategories(['sortBy' => request()->input('sortBy', 'oldest')]);

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created category.
     *
     * @return CategoryResource
     */
    public function store(): CategoryResource
    {
        $this->authorize('create', Category::class);

        request()->merge(['slug' => str(request()->input('name'))->slug()]);

        $validatedCategoryPayload = request()->validate(
            [
                'name' => ['required', 'min:1', 'max:255'],
                'slug' => ['required', 'min:1', 'max:255', 'unique:categories,slug'],
                'description' => ['nullable', 'min:1', 'max:500'],
            ]
        );

        return CategoryResource::make($this->categoryService->createCategory($validatedCategoryPayload));
    }

    /**
     * Get a specific category.
     *
     * @param  int  $categoryId
     * @return CategoryResource
     */
    public function show(int $categoryId): CategoryResource
    {
        return CategoryResource::make($this->categoryService->getCategoryById($categoryId));
    }

    /**
     * Update a specific category.
     *
     * @param  int  $categoryId
     * @return CategoryResource
     */
    public function update(int $categoryId): CategoryResource
    {
        $this->authorize('update', Category::class);

        request()->merge(['slug' => str(request()->input('name'))->slug()]);

        $validatedCategoryPayload = request()->validate(
            [
                'name' => ['required', 'min:1', 'max:255'],
                'slug' => ['min:1', 'max:255', 'unique:categories,slug'],
                'description' => ['nullable', 'min:1', 'max:500'],
            ]
        );

        return CategoryResource::make($this->categoryService->updateCategory($categoryId, $validatedCategoryPayload));
    }

    /**
     * Delete a specific category.
     *
     * @param  int  $categoryId
     * @return Response
     */
    public function destroy(int $categoryId): Response
    {
        $this->authorize('delete', Category::class);

        $this->categoryService->deleteCatgoryById($categoryId);

        return response()->noContent();
    }
}
