<?php

use Src\product\infrastructure\providers\ProductServiceProvider;
use Src\category\infrastructure\providers\CategoryServiceProvider;
use Src\coupon\infrastructure\providers\CouponServiceProvider;
use Src\supplier\infrastructure\providers\SupplierServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
    ProductServiceProvider::class,
    CategoryServiceProvider::class,
    CouponServiceProvider::class,
    SupplierServiceProvider::class,
    \Ecotone\Laravel\EcotoneProvider::class
];
