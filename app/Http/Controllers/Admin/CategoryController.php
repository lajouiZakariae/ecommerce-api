<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return Collection<int,Category>
     */
    public function index(): Collection
    {
        return $this->categoryService->getAllCategories();
    }

    public function store()
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

        $category = new Category($validatedCategoryPayload);

        if (!$category->save()) throw new BadRequestException("Category Could not be created");

        return $category;
    }

    public function update(int $category_id): Response
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

        $affectedRowsCount = Category::where('id', $category_id)->update($validatedCategoryPayload);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException("Category Not Found");

        return response()->noContent();
    }

    public function destroy(int $category_id): Response
    {
        $affectedRowsCount = Category::destroy($category_id);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException("Category Not Found");

        return response()->noContent();
    }
}
