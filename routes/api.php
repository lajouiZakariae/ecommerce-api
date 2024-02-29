<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponCodeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderItemController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Auth\JWTAuthController;
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

Route::middleware(['auth:api'])->get('/v1/user', fn (Request $request) => $request->user()->load('role'));


Route::controller(JWTAuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::group([
    'prefix' => 'v1',
    'middleware' => 'auth:api'
], function () {
    Route::apiResource('categories', CategoryController::class)->whereNumber('category');

    Route::apiResource('stores', StoreController::class)->whereNumber('store');

    Route::apiResource('products', ProductController::class)->whereNumber('product');

    Route::patch('products/{product}/toggle-publish', [ProductController::class, 'togglePublish'])->whereNumber('product');

    Route::get('categories/{category}/products', [ProductController::class, 'productsByCategory'])->whereNumber('category');

    Route::get('stores/{store}/products', [ProductController::class, 'productsByStore'])->whereNumber('store');

    Route::apiResource('coupon-codes', CouponCodeController::class)->whereNumber('coupon_code');

    Route::apiResource('orders', OrderController::class)->whereNumber('order');

    Route::patch('orders/{order}/cancel', [OrderController::class, 'cancelOrder']);

    Route::group(['prefix' => 'orders/{order}'], function () {
        Route::apiResource('order-items', OrderItemController::class)->whereNumber('order_item');
    });
});
