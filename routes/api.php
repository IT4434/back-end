<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\VerificationController;
use App\Http\Controllers\Customer\PasswordController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\ProductDetailController;

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

/**
 * Authentication routes
 */
Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth');
    Route::get('/email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::get('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::post('/password/forget', [PasswordController::class, 'forget']);
    Route::post('/password/change', [PasswordController::class, 'change'])->middleware('auth');
    Route::post('/password/reset', [PasswordController::class, 'reset'])->name('reset');
});

//Product routes
Route::group([
    'prefix' => 'products',
], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product}', [ProductController::class, 'show']);
    Route::get('/categories/{category}', [ProductController::class, 'getProductsByCategory']);
});

// Filter products
Route::get('/search-products', [ProductController::class, 'searchProduct']);
Route::get('/sort-products', [ProductController::class, 'sortProduct']);
