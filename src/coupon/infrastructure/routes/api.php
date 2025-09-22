<?php

use Illuminate\Support\Facades\Route;
use Src\coupon\infrastructure\controllers\CouponController;

Route::get('', [CouponController::class, 'index']);

Route::middleware(['auth:api'])->group(function () {

    Route::post('', [CouponController::class, 'store']);
    Route::delete('{code}', [CouponController::class, 'destroy']);
    Route::put('{code}', [CouponController::class, 'update']);
});
