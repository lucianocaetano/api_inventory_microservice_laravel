<?php

namespace Src\product\infrastructure\providers;

use Illuminate\Support\ServiceProvider;
use Src\category\application\contracts\in\CreateProductUseCasePort;
use Src\category\application\contracts\in\DeleteProductUseCasePort;
use Src\category\application\contracts\in\FindAllProductsUseCasePort;
use Src\category\application\contracts\in\FindBySlugProductUseCasePort;
use Src\category\application\contracts\in\UpdateProductUseCasePort;
use Src\product\application\contracts\out\ProductReadRepository;
use Src\product\application\use_cases\CreateProductUseCase;
use Src\product\application\use_cases\DeleteProductUseCase;
use Src\product\application\use_cases\FindAllProductsUseCase;
use Src\product\application\use_cases\FindBySlugProductUseCase;
use Src\product\application\use_cases\UpdateProductUseCase;

use Src\product\domain\repositories\ProductRepository;

use Src\product\infrastructure\repositories\EloquentProductWriteRepository;
use Src\product\infrastructure\repositories\EloquentProductReadRepository;

class ProductServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            ProductRepository::class,
            EloquentProductWriteRepository::class
        );

        $this->app->bind(
            ProductReadRepository::class,
            EloquentProductReadRepository::class
        );

        $this->app->bind(CreateProductUseCasePort::class, CreateProductUseCase::class);
        $this->app->bind(UpdateProductUseCasePort::class, UpdateProductUseCase::class);
        $this->app->bind(DeleteProductUseCasePort::class, DeleteProductUseCase::class);
        $this->app->bind(FindAllProductsUseCasePort::class, FindAllProductsUseCase::class);
        $this->app->bind(FindBySlugProductUseCasePort::class, FindBySlugProductUseCase::class);
    }

    public function boot()
    {
        //
    }
}
