<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductDetailController;

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
//    'middleware' => 'auth:admin',
    'prefix' => 'categories'
], function () {
   Route::get('/', [CategoryController::class, 'index']);
   Route::post('/', [CategoryController::class, 'store']);
   Route::get('/{category}', [CategoryController::class, 'show']);
   Route::put('/{category}', [CategoryController::class, 'update']);
   Route::delete('/{category}', [CategoryController::class, 'destroy']);
});

Route::group([
//    'middleware' => 'auth:admin',
    'prefix' => 'products'
], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{product}', [ProductController::class, 'show']);
    Route::put('/{product}', [ProductController::class, 'update']);
    Route::delete('/{product}', [ProductController::class, 'destroy']);
});

Route::group([
    //    'middleware' => 'auth:admin',
    'prefix' => 'products',
], function () {
    Route::get('/{product}/details', [ProductDetailController::class, 'index']);
    Route::post('/details', [ProductDetailController::class, 'store']);
    Route::get('/{product}/details/{detail}', [ProductDetailController::class, 'show']);
    Route::put('/{product}/details/{detail}', [ProductDetailController::class, 'update']);
    Route::delete('/{product}/details/{detail}', [ProductDetailController::class, 'delete']);
});

Route::group([
   'prefix' => 'images'
], function () {
    Route::post('/', [ProductController::class, 'updateImage']);
});

