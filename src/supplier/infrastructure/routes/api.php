<?php

use Illuminate\Support\Facades\Route;
use Src\supplier\infrastructure\controllers\SupplierController;

Route::get('', [SupplierController::class, 'index']);

Route::middleware(['auth:api'])->group(function () {

    Route::apiResource('', SupplierController::class)->except('show', "index");
});
