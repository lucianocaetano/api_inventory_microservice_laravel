<?php

namespace Src\supplier\infrastructure\providers;

use Illuminate\Support\ServiceProvider;
use Src\supplier\application\contracts\out\SupplierReadRepository;
use Src\supplier\application\use_cases\DeleteSupplierUseCase;
use Src\supplier\application\use_cases\UpdateSupplierUseCase;
use Src\supplier\application\use_cases\CreateSupplierUseCase;
use Src\supplier\application\use_cases\FindAllSuppliersUseCase;
use Src\supplier\domain\repositories\SupplierRepository;
use Src\supplier\infrastructure\repositories\EloquentSupplierReadRepository;
use Src\supplier\infrastructure\repositories\EloquentSupplierSupplierRepository;

class SupplierServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SupplierRepository::class, EloquentSupplierSupplierRepository::class);
        $this->app->bind(SupplierReadRepository::class, EloquentSupplierReadRepository::class);

        $this->app->bind(CreateSupplierUseCase::class);
        $this->app->bind(UpdateSupplierUseCase::class);
        $this->app->bind(DeleteSupplierUseCase::class);
        $this->app->bind(FindAllSuppliersUseCase::class);
    }

    public function boot()
    {
        //
    }
}
