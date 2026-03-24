<?php

use Illuminate\Support\Facades\Route;
use Sndpbag\AdminPanel\Http\Controllers\Api\ApiAuthController;
use Sndpbag\AdminPanel\Http\Controllers\Dashboard\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api/v1')->group(function () {

    // Public Routes
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/verify-otp', [ApiAuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [ApiAuthController::class, 'resendOtp']);
    Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [ApiAuthController::class, 'resetPassword']);

    // Protected Routes (Require Sanctum Token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [ApiAuthController::class, 'me']);
        Route::post('/logout', [ApiAuthController::class, 'logout']);
        Route::post('/resend-verification', [ApiAuthController::class, 'resendVerification']);

        // Dashboard Stats
        Route::get('/stats', [DashboardController::class, 'apiStats']);

        // Dashboard CRUDs (Hack Protected with Permission Middleware)
        Route::middleware('permission:admin')->group(function () {
            // User CRUD
            Route::apiResource('users', \Sndpbag\AdminPanel\Http\Controllers\Api\Dashboard\UserController::class);
            
            // Role CRUD
            Route::apiResource('roles', \Sndpbag\AdminPanel\Http\Controllers\Api\Dashboard\RoleController::class);
            
            // Permissions Read-only
            Route::get('permissions', [\Sndpbag\AdminPanel\Http\Controllers\Api\Dashboard\PermissionController::class, 'index']);
            Route::get('permissions/{permission}', [\Sndpbag\AdminPanel\Http\Controllers\Api\Dashboard\PermissionController::class, 'show']);
        });
    });

});
