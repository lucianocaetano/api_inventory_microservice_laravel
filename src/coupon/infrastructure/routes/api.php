<?php

use Illuminate\Support\Facades\Route;
use Src\coupon\infrastructure\controllers\CouponController;
use Src\shared\infrastructure\middleware\ValidTokenMiddleware;

Route::middleware(ValidTokenMiddleware::class)->group(function () {

    Route::apiResource('', CouponController::class)->except('show');
});
