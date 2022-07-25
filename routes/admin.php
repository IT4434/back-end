<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductDetailController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StaticsController;

//Authentication Routes
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth');
});

//Category Routes
Route::group([
    'middleware' => 'auth:admin',
    'prefix' => 'categories'
], function () {
   Route::get('/', [CategoryController::class, 'index']);
   Route::post('/', [CategoryController::class, 'store']);
   Route::get('/{category}', [CategoryController::class, 'show']);
   Route::put('/{category}', [CategoryController::class, 'update']);
   Route::delete('/{category}', [CategoryController::class, 'destroy']);
});

Route::group([
    'middleware' => 'auth:admin',
    'prefix' => 'products'
], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{product}', [ProductController::class, 'show']);
    Route::put('/{product}', [ProductController::class, 'update']);
    Route::delete('/{product}', [ProductController::class, 'destroy']);
});

Route::group([
    'middleware' => 'auth:admin',
    'prefix' => 'products',
], function () {
    Route::get('/{product}/details', [ProductDetailController::class, 'index']);
    Route::post('/details', [ProductDetailController::class, 'store']);
    Route::get('/details/{productDetail}', [ProductDetailController::class, 'show']);
    Route::put('/details/{productDetail}', [ProductDetailController::class, 'update']);
    Route::delete('/details/{productDetail}', [ProductDetailController::class, 'destroy']);
});

Route::group([
   'prefix' => 'images'
], function () {
    Route::post('/', [ProductController::class, 'updateImage']);
});

// Order routes
Route::group([
    'prefix' => 'orders',
    'middleware' => 'auth:admin',
], function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{order}', [OrderController::class, 'show']);
    Route::put('/{order}', [OrderController::class, 'updateOrderStatus']);
    Route::delete('/{order}', [OrderController::class, 'destroy']);
});

// User routes
Route::group([
    'prefix' => 'users',
    'middleware' => 'auth:admin',
], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/{user}', [UserController::class, 'blockUser']);
});

// Static Route
Route::group([
    'prefix' => 'statics',
    'middleware' => 'auth:admin',
], function () {
    Route::get('/month-top-product', [StaticsController::class, 'showTopProductInMonth']);
    Route::get('/week-top-product', [StaticsController::class, 'showTopProductInWeek']);
});

