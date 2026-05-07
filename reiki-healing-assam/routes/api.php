<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/login', [AuthController::class, 'login']);

// Authenticated client routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(['subscription.active'])->group(function () {
        Route::get('/categories', [ModuleController::class, 'categories']);
        Route::get('/categories/{category}/subcategories', [ModuleController::class, 'subcategories']);
        Route::post('/subcategories/solutions', [ModuleController::class, 'solutionsPreview']);
        Route::get('/solutions/search', [ModuleController::class, 'searchSolutions']);
        Route::get('/reports', [ReportController::class, 'index']);
        Route::get('/reports/{report}/download', [ReportController::class, 'download']);
        Route::post('/reports/generate', [ReportController::class, 'generate']);
    });
});
