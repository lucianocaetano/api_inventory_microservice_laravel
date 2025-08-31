<?php

use Illuminate\Support\Facades\Route;
use Src\shared\infrastructure\middleware\ValidTokenMiddleware;
use Src\supplier\infrastructure\controllers\SupplierController;

Route::middleware(ValidTokenMiddleware::class)->apiResource('', SupplierController::class)->except('show');
