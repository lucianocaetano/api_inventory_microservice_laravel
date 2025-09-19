<?php

namespace Src\payment\infrastructure\providers;

use Illuminate\Support\ServiceProvider;
use Src\payment\application\use_cases\CreatePaymentUseCase;
use Src\payment\application\use_cases\ListPaymentsUseCase;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ListPaymentsUseCase::class);
        $this->app->bind(CreatePaymentUseCase::class);
    }

    public function boot()
    {
        //
    }
}
