<?php

use App\Http\Controllers\AccountPlan;
use App\Http\Controllers\CostCenter;
use App\Http\Controllers\MeController;
use App\Http\Controllers\Profile;
use App\Http\Controllers\Transaction;
use App\Http\Controllers\TwoFactor;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Me Routes
     */
    Route::get('me', MeController::class);

    /**
     * Profile
     */
    Route::prefix('/profile')->group(function () {
        Route::put('/', Profile\UpdateProfileController::class);

        Route::patch('/password', Profile\UpdatePasswordController::class);

        Route::delete('/', Profile\DestroyAccountController::class)->middleware('password.confirm');
    });

    /**
     * Two Factor Authentication
     */
    Route::prefix('/two-factor')->middleware('password.confirm')->group(function () {
        Route::post('/enable', TwoFactor\EnableAuthenticationController::class);

        Route::post('/confirm', TwoFactor\ConfirmAuthenticationController::class);

        Route::delete('/destroy', TwoFactor\DestroyAuthenticationController::class);

        Route::get('/qr-code', [TwoFactor\IndexController::class, 'qrCode']);

        Route::get('/secret-key', [TwoFactor\IndexController::class, 'secretKey']);

        Route::get('/recovery-codes', [TwoFactor\IndexController::class, 'recoveryCodes']);

        Route::post('/recovery-codes', TwoFactor\NewRecoveryCodesController::class);
    });

    /**
     * Account Plan Routes
     */
    Route::prefix('/account-plans')->middleware('verified')->group(function () {
        Route::get('/', AccountPlan\IndexController::class);

        Route::post('/', AccountPlan\StoreController::class);

        Route::get('/{account_plan}', AccountPlan\ShowController::class);

        Route::put('/{account_plan}', AccountPlan\UpdateController::class);

        Route::delete('/{account_plan}', AccountPlan\DestroyController::class);
    });

    /**
     * Cost Center Routes
     */
    Route::prefix('/cost-centers')->middleware('verified')->group(function () {
        Route::get('/', CostCenter\IndexController::class);

        Route::post('/', CostCenter\StoreController::class);

        Route::get('/{cost_center}', CostCenter\ShowController::class);

        Route::put('/{cost_center}', CostCenter\UpdateController::class);

        Route::delete('/{cost_center}', CostCenter\DestroyController::class);
    });

    /**
     * Transaction Routes
     */
    Route::prefix('/transactions')->middleware('verified')->group(function () {
        Route::get('/', Transaction\IndexController::class);

        Route::post('/', Transaction\StoreController::class);

        Route::get('/{transaction}', Transaction\ShowController::class);

        Route::put('/{transaction}', Transaction\UpdateController::class);

        Route::delete('/{transaction}', Transaction\DestroyController::class);
    });
});
