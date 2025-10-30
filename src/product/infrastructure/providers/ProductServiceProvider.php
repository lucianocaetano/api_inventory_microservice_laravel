<?php

namespace Src\product\infrastructure\providers;

use Illuminate\Support\ServiceProvider;


use Src\category\application\contracts\out\CategoryReadRepository;

use Src\product\application\contracts\in\CreateProductUseCasePort;
use Src\product\application\contracts\in\DeleteProductUseCasePort;
use Src\product\application\contracts\in\FindAllProductsUseCasePort;
use Src\product\application\contracts\in\FindBySlugProductUseCasePort;
use Src\product\application\contracts\in\UpdateProductUseCasePort;
use Src\product\application\contracts\out\ProductReadRepository;
use Src\product\application\use_cases\CreateProductUseCase;
use Src\product\application\use_cases\DeleteProductUseCase;
use Src\product\application\use_cases\FindAllProductsUseCase;
use Src\product\application\use_cases\FindBySlugProductUseCase;
use Src\product\application\use_cases\UpdateProductUseCase;

use Src\product\domain\repositories\ProductRepository;

use Src\product\infrastructure\decorators\CreateProductUseCaseEventPublisherDecorator;
use Src\product\infrastructure\decorators\DeleteProductUseCaseEventPublisherDecorator;
use Src\product\infrastructure\decorators\UpdateProductUseCaseEventPublisherDecorator;
use Src\product\infrastructure\repositories\EloquentProductWriteRepository;
use Src\product\infrastructure\repositories\EloquentProductReadRepository;

use Src\shared\application\contracts\out\EventPublisher;
use Src\shared\application\contracts\out\ExtractCurrentUser;

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

        $this->app->bind(CreateProductUseCasePort::class, function() {

            return new CreateProductUseCaseEventPublisherDecorator(
                new CreateProductUseCase(
                    $this->app->make(ProductRepository::class),
                    $this->app->make(CategoryReadRepository::class),
                    $this->app->make(ExtractCurrentUser::class),
                ),
                $this->app->make(EventPublisher::class),
                $this->app->make(CategoryReadRepository::class),
            );
        });

        $this->app->bind(UpdateProductUseCasePort::class, function() {

            return new UpdateProductUseCaseEventPublisherDecorator(
                new UpdateProductUseCase(
                    $this->app->make(CategoryReadRepository::class),
                    $this->app->make(ProductRepository::class),
                    $this->app->make(ExtractCurrentUser::class)
                ),
                $this->app->make(EventPublisher::class),
                $this->app->make(CategoryReadRepository::class),
            );
        });

        $this->app->bind(DeleteProductUseCasePort::class, function () {

            return new DeleteProductUseCaseEventPublisherDecorator(
                new DeleteProductUseCase(
                    $this->app->make(ProductRepository::class),
                    $this->app->make(ExtractCurrentUser::class)
                ),
                $this->app->make(EventPublisher::class)
            );
        });

        $this->app->bind(FindAllProductsUseCasePort::class, FindAllProductsUseCase::class);
        $this->app->bind(FindBySlugProductUseCasePort::class, FindBySlugProductUseCase::class);
    }

    public function boot()
    {
        //
    }
}
