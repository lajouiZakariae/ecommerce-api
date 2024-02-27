<?php

namespace App\Services;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\Category;
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
     * @return Collection
     */
    public function getStoreById(int $categoryId): Collection
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
     * @return bool
     */
    public function updateCategory(int $categoryId, array $categoryPayload): bool
    {
        $affectedRowsCount = Category::where('id', $categoryId)->update($categoryPayload);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }


    /**
     * @param int $storeId
     * @return bool
     */
    public function deleteCatgoryById(int $categoryId): bool
    {
        $affectedRowsCount = Category::destroy($categoryId);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }
}
