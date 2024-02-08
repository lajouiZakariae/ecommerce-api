<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * @group Categories
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    #[Get('/categories')]
    public function index(): Response
    {
        $categories = Category::query();

        $categories = request()->input("sortBy") === "oldest"
            ? $categories->oldest()
            : $categories->latest();

        return response($categories->get());
    }
}
