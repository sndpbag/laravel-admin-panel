<?php

use Illuminate\Support\Facades\Route;

// Namespace for Auth Controllers
use Sndpbag\AdminPanel\Http\Controllers\Auth\AuthenticatedSessionController;
use Sndpbag\AdminPanel\Http\Controllers\Auth\CaptchaController;
use Sndpbag\AdminPanel\Http\Controllers\Auth\NewPasswordController;
use Sndpbag\AdminPanel\Http\Controllers\Auth\OtpController;
use Sndpbag\AdminPanel\Http\Controllers\Auth\PasswordResetLinkController;
use Sndpbag\AdminPanel\Http\Controllers\Auth\RegisteredUserController;
use Sndpbag\AdminPanel\Http\Controllers\Auth\VerificationController;

// Namespace for Dashboard Controllers
use Sndpbag\AdminPanel\Http\Controllers\Dashboard\DashboardController;
use Sndpbag\AdminPanel\Http\Controllers\Dashboard\SettingsController;
use Sndpbag\AdminPanel\Http\Controllers\Dashboard\UserController;
use Sndpbag\AdminPanel\Http\Controllers\Dashboard\UserLogController;


/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

Route::middleware('web')->group(function () {

    Route::middleware('auth')->group(function () {
        // --- Dashboard Routes ---
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Users Routes
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggleStatus');
            Route::patch('/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('toggleRole');
            Route::get('/export/{type}', [UserController::class, 'export'])->name('export');
            Route::post('/import', [UserController::class, 'import'])->name('import');
            Route::get('/import-template', [UserController::class, 'downloadTemplate'])->name('template');
            Route::get('/trashed', [UserController::class, 'trashed'])->name('trashed');
            Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete'])->name('forceDelete');

            // Permissions Management
            Route::put('/{user}/permissions', [UserController::class, 'updatePermissions'])->name('permissions.update');

            // Role Management (AJAX)
            Route::patch('/{user}/role', [UserController::class, 'updateRole'])->name('role.update');
        });

        // Settings Routes
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
            Route::post('/password', [SettingsController::class, 'updatePassword'])->name('password.update');
            Route::post('/theme', [SettingsController::class, 'updateTheme'])->name('theme.update');
            Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
            Route::post('/profile-image', [SettingsController::class, 'updateProfileImage'])->name('profile-image.update');
            Route::post('/profile-image', [SettingsController::class, 'updateProfileImage'])->name('profile-image.update');
        });

        // Roles & Permissions Routes
        Route::resource('roles', \Sndpbag\AdminPanel\Http\Controllers\Dashboard\RoleController::class);

        // User Logs Route
        Route::get('/user-logs', [UserLogController::class, 'index'])->name('user-logs.index');

        // --- Authentication Routes ---


    });


    Route::middleware('guest')->group(function () {


        // Register Routes
        Route::get('register', [RegisteredUserController::class, 'create'])
            ->name('register');

        Route::post('register', [RegisteredUserController::class, 'store']);

        // login captcha
        Route::get('captcha-gen', [CaptchaController::class, 'generate'])->name('captcha.generate');
        // Login Routes
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        // Forgot Password Routes
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        // Reset Password Routes
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.update');
    });

    Route::get('verify-otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('verify-otp/resend', [OtpController::class, 'resend'])->name('otp.resend');





    // যে ব্যবহারকারীরা লগইন করেছেন, শুধু তারাই এই রাউটগুলো ব্যবহার করতে পারবেন
    Route::middleware('auth')->group(function () {

        // Logout Route
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        // Notice page ebong resend email link (login kora user-der jonno)
        Route::get('verify-email', [VerificationController::class, 'notice'])->name('verification.notice');
    });

    Route::post('email/verification-notification', [VerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // --- Public Verification Route (login kora chara'i kaj korbe) ---
    // Guruttwopurno: Ei route-tike 'auth' group-er baire rakha hoyeche
    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');


    // Public verification link (works without being logged in)
    // Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
    //     ->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');
});
