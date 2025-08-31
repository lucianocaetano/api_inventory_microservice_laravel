<?php

namespace Src\coupon\infrastructure\providers;

use Illuminate\Support\ServiceProvider;
use Src\coupon\application\contracts\out\CouponReadRepository;
use Src\coupon\domain\repositories\CouponRepository;
use Src\coupon\infrastructure\repositories\EloquentCouponWriteRepository;

use Src\coupon\application\use_cases\CreateCouponUseCase;
use Src\coupon\application\use_cases\FindAllCouponsUseCase;
use Src\coupon\application\use_cases\DeleteCouponUseCase;
use Src\coupon\application\use_cases\UpdateCouponUseCase;
use Src\coupon\infrastructure\repositories\EloquentCouponReadRepository;

class CouponServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CouponRepository::class, EloquentCouponWriteRepository::class);
        $this->app->bind(CouponReadRepository::class, EloquentCouponReadRepository::class);

        $this->app->bind(FindAllCouponsUseCase::class);
        $this->app->bind(CreateCouponUseCase::class);
        $this->app->bind(UpdateCouponUseCase::class);
        $this->app->bind(DeleteCouponUseCase::class);
    }

    public function boot()
    {
        //
    }
}
