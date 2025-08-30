<?php

use Illuminate\Support\Facades\Route;
use Src\product\infrastructure\controllers\ProductController;
use Src\shared\infrastructure\middleware\ValidTokenMiddleware;

Route::middleware(ValidTokenMiddleware::class)->group(function () {

    Route::get('', [ProductController::class, 'index']);
    Route::get('/{slug}', [ProductController::class, 'show']);
    Route::post('', [ProductController::class, 'save']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'delete']);
});
