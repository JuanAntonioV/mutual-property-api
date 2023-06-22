<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Analytics\AdminAnalyticsController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Contacts\AdminContactController;
use App\Http\Controllers\Admin\Product\AdminProductController;
use App\Http\Controllers\Admin\Projects\AdminProjectController;
use App\Http\Controllers\Admin\Subscriptions\AdminSubcriptionController;
use App\Http\Controllers\Auth\AuthController;
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
        });

        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);

        Route::post('change-password', [AuthController::class, 'changePassword']);
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

    Route::post('/subscriptions', [AdminSubcriptionController::class, 'createSubscription']);

    Route::post('/contacts', [AdminContactController::class, 'sendNewContact']);

    Route::prefix('admin')->group(callback: function () {
        Route::prefix('auth')->group(function () {
            Route::post('login', [AdminAuthController::class, 'login']);

            Route::middleware('auth:sanctum')->group(function () {
                Route::post('logout', [AdminAuthController::class, 'logout']);
                Route::get('me', [AdminAuthController::class, 'me']);
            });

            Route::post('forgot-password', [AdminAuthController::class, 'forgotPassword']);
            Route::post('reset-password', [AdminAuthController::class, 'resetPassword']);
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('profile', [AdminAuthController::class, 'getAdminProfile']);
            Route::post('profile', [AdminAuthController::class, 'updateAdminProfile']);

            Route::post('change-password', [AdminAuthController::class, 'changePassword']);

            Route::prefix('products')->group(function () {
                Route::get('/', [AdminProductController::class, 'getAllProducts']);
                Route::get('/{id}', [AdminProductController::class, 'getProductDetails']);
                Route::post('/', [AdminProductController::class, 'createProduct']);
                Route::post('/{id}/update', [AdminProductController::class, 'updateProduct']);
                Route::delete('/{id}', [AdminProductController::class, 'deleteProduct']);
            });
        });

        Route::get('/stats', [AdminAnalyticsController::class, 'getStats']);
        Route::get('/contacts', [AdminContactController::class, 'getAllContacts']);
        Route::get('/subscriptions', [AdminSubcriptionController::class, 'getAllSubscriptions']);

        Route::get('/', [AdminController::class, 'getAllAdmins']);

        Route::prefix('super')->group(function () {
            Route::post('/', [AdminController::class, 'createAdmin']);
            Route::get('/{id}', [AdminController::class, 'getAdminDetails']);
            Route::put('/{id}', [AdminController::class, 'updateAdmin']);
            Route::put('/{id}/change-password', [AdminController::class, 'changeAdminPassword']);
            Route::put('/{id}/non-active', [AdminController::class, 'nonActiveAdmin']);
        });

        Route::prefix('projects')->group(function () {
            Route::get('/', [AdminProjectController::class, 'getAllProjects']);
            Route::get('/{id}', [AdminProjectController::class, 'getProjectDetails']);
            Route::post('/', [AdminProjectController::class, 'createNewProject']);
            Route::post('/{id}', [AdminProjectController::class, 'updateProject']);
            Route::delete('/{id}', [AdminProjectController::class, 'deleteProject']);
        });
    });
});
