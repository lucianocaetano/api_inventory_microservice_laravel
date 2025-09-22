<?php

use Illuminate\Support\Facades\Route;
use Src\payment\infrastructure\controllers\PaymentController;

Route::middleware(['auth:api'])->group(
    function () {
        Route::get('/payment', [PaymentController::class, "index"]);
        Route::post('/payment', [PaymentController::class, "create"]);
    }
);
