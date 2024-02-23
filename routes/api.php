<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/admin/user', function (Request $request) {
    return $request->user()->load('role');
});

Route::group([
    'prefix' => 'admin',
    'middlewear' => 'auth.sanctum'
], function () {
    Route::apiResource('products', ProductController::class)->whereNumber('product');

    Route::patch('products/{product}/toggle-publish', [ProductController::class, 'togglePublish'])->whereNumber('product');

    Route::get('categories/{category}/products', [ProductController::class, 'productsByCategory'])->whereNumber('category');

    Route::get('stores/{store}/products', [ProductController::class, 'productsByStore'])->whereNumber('store');

    Route::apiResource('orders', OrderController::class)->whereNumber('order');

    Route::patch('orders/{order}/cancel', [OrderController::class, 'cancelOrder']);
});
