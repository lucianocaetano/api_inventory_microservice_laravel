<?php

use Illuminate\Support\Facades\Route;
use Src\shared\infrastructure\middleware\ValidTokenMiddleware;
use Src\supplier\infrastructure\controllers\SupplierController;

Route::get('', [SupplierController::class, 'index']);

Route::middleware(ValidTokenMiddleware::class)->group(function () {

    Route::apiResource('', SupplierController::class)->except('show', "index");
});
