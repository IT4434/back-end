<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;

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
