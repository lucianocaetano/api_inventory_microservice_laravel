<?php

use Illuminate\Support\Facades\Route;
use Src\payment\infrastructure\controllers\PaymentController;
use Src\shared\infrastructure\middleware\ValidTokenMiddleware;

Route::middleware(ValidTokenMiddleware::class)->group(
    function () {
        Route::get('/payment', [PaymentController::class, "index"]);
        Route::post('/payment', [PaymentController::class, "create"]);
    }
);
