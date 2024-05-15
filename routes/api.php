<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\V1\CurrencyController;
use App\Http\Controllers\Api\Admin\V1\FrameController;
use App\Http\Controllers\Api\Admin\V1\LensController;
use App\Http\Controllers\Api\V1\FrameController as UserFrameController;
use App\Http\Controllers\Api\V1\GlassesController;
use App\Http\Controllers\Api\V1\LensController as UserLensController;
use App\Http\Middleware\isAdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    /** Public Routes */
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    /** Private Routes */
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
});

Route::prefix('admin')->middleware(['auth:sanctum', isAdminMiddleware::class])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::resource('frames', FrameController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
        Route::resource('lenses', LensController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    });
});

Route::prefix('v1')->group(function () {
    /** Public Routes */
    Route::resource('currencies', CurrencyController::class)->only('index');

    /**Private Routes */
    Route::middleware('auth:sanctum')->group(function(){
        Route::resource('frames', UserFrameController::class)->only(['index', 'show']);
        Route::resource('lenses', UserLensController::class)->only(['index', 'show']);
        Route::post('glasses', [GlassesController::class, 'create_pair']);
    });
});