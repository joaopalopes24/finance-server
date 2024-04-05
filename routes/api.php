<?php

use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Auth Routes
 */
Route::middleware('guest')->group(function () {
    Route::post('login', [Auth\AuthenticatedSessionController::class, 'store']);

    Route::post('register', Auth\RegisteredUserController::class);

    Route::post('forgot-password', Auth\PasswordResetLinkController::class);

    Route::patch('reset-password', Auth\NewPasswordController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('logout', [Auth\AuthenticatedSessionController::class, 'destroy']);

    Route::post('email/new-notification', Auth\EmailVerificationNotificationController::class)->middleware('throttle:6,1');
});

Route::get('verify-email/{id}/{hash}', Auth\VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
