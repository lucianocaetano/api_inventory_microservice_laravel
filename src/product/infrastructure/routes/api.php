<?php

use Illuminate\Support\Facades\Route;
use Src\product\infrastructure\controllers\ProductController;

Route::get('', [ProductController::class, 'index']);
Route::get('/{slug}', [ProductController::class, 'show']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('', [ProductController::class, 'save']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'delete']);
});
