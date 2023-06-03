<?php

use App\Http\Controllers\Auth\AuthController;
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
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('me', [AuthController::class, 'me']);

        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
    });

    Route::prefix('user')->group(function () {
        Route::get('/profile', [UserController::class, 'getUserProfile']);
        Route::put('/{id}', [UserController::class, 'updateUserProfile']);
    });
});
