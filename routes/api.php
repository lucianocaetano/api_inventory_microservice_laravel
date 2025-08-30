<?php

use Illuminate\Support\Facades\Route;

Route::prefix("product")->group(base_path('src/product/infrastructure/routes/api.php'));
Route::prefix("category")->group(base_path('src/category/infrastructure/routes/api.php'));
Route::prefix("coupon")->group(base_path('src/coupon/infrastructure/routes/api.php'));
Route::prefix("payment")->group(base_path('src/payment/infrastructure/routes/api.php'));
Route::prefix("supplier")->group(base_path('src//supplier/infrastructure/routes/api.php'));