<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Category\CategoriesController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', [WelcomeController::class, 'appDetails']);

Route::prefix('v1')->group(function () {
    Route::get('/', [WelcomeController::class, 'appDetails']);

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });

        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
    });

    Route::prefix('user')->middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [UserController::class, 'getUserProfile']);
        Route::patch('/profile', [UserController::class, 'updateUserProfile']);
    });

    Route::get('search', [ProductController::class, 'searchProduct']);
    Route::get('newest-products', [ProductController::class, 'getNewProductPosts']);
    Route::get('products', [ProductController::class, 'getAllProducts']);
    Route::get('developer-products', [ProductController::class, 'getDeveloperProducts']);
    Route::get('products/{slug}', [ProductController::class, 'getProductDetails']);
});
