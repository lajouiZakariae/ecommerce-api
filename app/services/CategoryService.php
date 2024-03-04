<?php

namespace App\Services;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\Category;
use App\Models\Product;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class CategoryService
{
    private $notFoundMessage = "Category Not Found";

    /**
     * @return Collection
     */
    public function getAllCategories(array $filters): Collection
    {
        $categories = Category::query();

        $categories =  $filters['sortBy'] === "oldest"
            ? $categories->oldest()
            : $categories->latest();

        return $categories->get();
    }

    /**
     * @return Category
     */
    public function getCategoryById(int $categoryId): Category
    {
        $category = Category::find($categoryId);

        if ($category === null) throw new ResourceNotFoundException($this->notFoundMessage);

        return $category;
    }


    /**
     * @param array $storePayload
     * @return Category
     */
    public function createCategory(array $categoryPayload): Category
    {
        $category = new Category($categoryPayload);

        if (!$category->save()) throw new BadRequestException("Category Could not be created");

        return $category;
    }

    /**
     * @param int $categoryId
     * @param array $categoryPayload
     * @return Category
     */
    public function updateCategory(int $categoryId, array $categoryPayload): Category
    {
        $affectedRowsCount = Category::where('id', $categoryId)->update($categoryPayload);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return Category::find($categoryId);
    }


    /**
     * @param int $storeId
     * @return void
     */
    public function deleteCatgoryById(int $categoryId): void
    {
        if ($categoryId === 1) throw new BadRequestException("Category cannot be deleted");

        DB::transaction(function () use ($categoryId) {
            Product::where('category_id', $categoryId)->delete();

            $affectedRowsCount = Category::destroy($categoryId);

            if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);
        });
    }
}
