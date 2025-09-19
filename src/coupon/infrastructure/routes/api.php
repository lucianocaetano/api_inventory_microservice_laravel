<?php

use Illuminate\Support\Facades\Route;
use Src\coupon\infrastructure\controllers\CouponController;
use Src\shared\infrastructure\middleware\ValidTokenMiddleware;

Route::get('', [CouponController::class, 'index']);

Route::middleware(ValidTokenMiddleware::class)->group(function () {

    Route::post('', [CouponController::class, 'store']);
    Route::delete('{code}', [CouponController::class, 'destroy']);
    Route::put('{code}', [CouponController::class, 'update']);
});
