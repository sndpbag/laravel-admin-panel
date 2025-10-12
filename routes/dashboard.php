<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\UserLogController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Users Routes
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggleStatus');
    Route::patch('/users/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('toggleRole');
    Route::get('/users/export/{type}', [UserController::class, 'export'])->name('export');
    // import

    Route::post('/users/import', [UserController::class, 'import'])->name('import');
    Route::get('/users/import-template', [UserController::class, 'downloadTemplate'])->name('template');

    // soft delete
    Route::get('/users/trashed', [UserController::class, 'trashed'])->name('trashed');
Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('restore');
Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('forceDelete');
});

// Settings Routes
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password', [SettingsController::class, 'updatePassword'])->name('password.update');
    Route::post('/theme', [SettingsController::class, 'updateTheme'])->name('theme.update');
    Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
    Route::post('/profile-image', [SettingsController::class, 'updateProfileImage'])->name('profile-image.update');
});


Route::get('/login', function () {
    // আপনি এখানে welcome পেজ বা login পেজে redirect করতে পারেন
    return redirect()->route('login');
});

Route::prefix('dashboard')->name('user-logs.')->group(function () {
    // ... your other dashboard routes like 'dashboard', 'settings.index', etc.

    // Add this new route for user logs
    Route::get('/user-logs', [UserLogController::class, 'index'])->name('index');
});