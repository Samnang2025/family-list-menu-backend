<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\ImportController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PublicController;
use Illuminate\Support\Facades\Route;

// Public API (no authentication required)
Route::prefix('v1')->group(function () {
    Route::get('/settings', [PublicController::class, 'settings']);
    Route::get('/categories', [PublicController::class, 'categories']);
    Route::get('/products', [PublicController::class, 'products']);
    Route::get('/search', [PublicController::class, 'search']);
});

// Admin authentication
Route::prefix('v1/admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('products', ProductController::class);
        Route::post('products/{product}', [ProductController::class, 'update']);
        Route::apiResource('users', UserController::class);
        Route::post('users/{user}', [UserController::class, 'update']);

        Route::get('/settings', [SettingController::class, 'show']);
        Route::post('/settings', [SettingController::class, 'update']);
        Route::put('/settings', [SettingController::class, 'update']);

        Route::get('/import/template', [ImportController::class, 'template']);
        Route::post('/import', [ImportController::class, 'import']);
    });
});
