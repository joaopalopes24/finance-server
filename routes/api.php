<?php

use App\Http\Controllers\AccountPlan;
use App\Http\Controllers\CostCenter;
use App\Http\Controllers\MeController;
use App\Http\Controllers\Transaction;
use Illuminate\Support\Facades\Route;

/**
 * Me Routes
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', MeController::class);
});

/**
 * Account Plan Routes
 */
Route::prefix('/account-plans')->middleware('auth:sanctum')->group(function () {
    Route::get('/', AccountPlan\IndexController::class);

    Route::post('/', AccountPlan\StoreController::class);

    Route::get('/{account_plan}', AccountPlan\ShowController::class);

    Route::put('/{account_plan}', AccountPlan\UpdateController::class);

    Route::delete('/{account_plan}', AccountPlan\DestroyController::class);
});

/**
 * Cost Center Routes
 */
Route::prefix('/cost-centers')->middleware('auth:sanctum')->group(function () {
    Route::get('/', CostCenter\IndexController::class);

    Route::post('/', CostCenter\StoreController::class);

    Route::get('/{cost_center}', CostCenter\ShowController::class);

    Route::put('/{cost_center}', CostCenter\UpdateController::class);

    Route::delete('/{cost_center}', CostCenter\DestroyController::class);
});

/**
 * Transaction Routes
 */
Route::prefix('/transactions')->middleware('auth:sanctum')->group(function () {
    Route::get('/', Transaction\IndexController::class);

    Route::post('/', Transaction\StoreController::class);

    Route::get('/{transaction}', Transaction\ShowController::class);

    Route::put('/{transaction}', Transaction\UpdateController::class);

    Route::delete('/{transaction}', Transaction\DestroyController::class);
});
