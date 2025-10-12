<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

// যে ব্যবহারকারীরা লগইন করেননি, শুধু তারাই এই রাউটগুলো দেখতে পারবেন
Route::middleware('guest')->group(function () {
    
    // Register Routes
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);
    
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


// Route::get('verify-email', [VerificationController::class, '__invoke'])
//     ->middleware('auth')
//     ->name('verification.notice');

// // 2. User jokhon email theke link-e click kore, tokhon ei route-ti kaj kore
// Route::get('verify-email/{id}/{hash}', [VerificationController::class, '__invoke'])
//     ->middleware(['auth', 'signed', 'throttle:6,1'])
//     ->name('verification.verify');

// // 3. User jokhon "Resend Verification Email" button-e click kore
// Route::post('email/verification-notification', [VerificationController::class, 'store'])
//     ->middleware(['auth', 'throttle:6,1'])
//     ->name('verification.send');

 
  Route::get('verify-otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('verify-otp', [OtpController::class, 'verify'])->name('otp.verify');





// যে ব্যবহারকারীরা লগইন করেছেন, শুধু তারাই এই রাউটগুলো ব্যবহার করতে পারবেন
Route::middleware('auth')->group(function () {
    
    // Logout Route
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

                    // Notice page ebong resend email link (login kora user-der jonno)
    Route::get('verify-email', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::post('email/verification-notification', [VerificationController::class, 'send'])
                ->middleware('throttle:6,1')
                ->name('verification.send');
});


// --- Public Verification Route (login kora chara'i kaj korbe) ---
// Guruttwopurno: Ei route-tike 'auth' group-er baire rakha hoyeche
Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
