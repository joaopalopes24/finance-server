<?php

use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => ['Laravel' => app()->version()]);

Route::middleware('guest')->group(function () {
    Route::post('/login', [Auth\AuthenticatedSessionController::class, 'store']);

    Route::post('/register', Auth\RegisteredUserController::class);

    Route::post('/forgot-password', Auth\PasswordResetLinkController::class);

    Route::patch('/reset-password', Auth\NewPasswordController::class);

    Route::post('/two-factor', Auth\TwoFactorAuthenticatedController::class);
});

Route::middleware('auth')->group(function () {
    Route::delete('/logout', [Auth\AuthenticatedSessionController::class, 'destroy']);

    Route::get('/confirmed-status', [Auth\ConfirmablePasswordController::class, 'show']);

    Route::post('/confirm-password', [Auth\ConfirmablePasswordController::class, 'store']);

    Route::middleware('throttle:6,1')->group(function () {
        Route::get('/verify-email/{id}/{hash}', Auth\VerifyEmailController::class)->middleware('signed')->name('verification.verify');

        Route::post('/email/new-notification', Auth\EmailVerificationNotificationController::class);
    });
});
