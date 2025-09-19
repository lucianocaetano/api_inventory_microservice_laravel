<?php

use Src\category\infrastructure\controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Src\shared\infrastructure\middleware\ValidTokenMiddleware;

Route::get('', [CategoryController::class, 'index']);
Route::get('/{category_slug}/products', [CategoryController::class, 'find_all_with_products']);
Route::get('{slug}', [CategoryController::class, 'show']);

Route::middleware(ValidTokenMiddleware::class)->group(function () {

    Route::post('', [CategoryController::class, 'store']);
    Route::put('{slug}', [CategoryController::class, 'update']);
    Route::delete('{slug}', [CategoryController::class, 'destroy']);
});
