<?php

namespace Src\category\infrastructure\providers;

use Illuminate\Support\ServiceProvider;
use Src\category\application\contracts\in\CreateCategoryUseCasePort;
use Src\category\application\contracts\in\DeleteCategoryUseCasePort;
use Src\category\application\contracts\in\FindAllCategoriesUseCasePort;
use Src\category\application\contracts\in\FindBySlugCategoryUseCasePort;
use Src\category\application\contracts\in\FindBySlugWithProductsUseCasePort;
use Src\category\application\contracts\in\UpdateCategoryUseCasePort;
use Src\category\application\contracts\out\CategoryReadRepository;
use Src\category\domain\repositories\CategoryRepository;

use Src\category\infrastructure\repositories\EloquentCategoryWriteRepository;
use Src\category\infrastructure\repositories\EloquentCategoryReadRepository;

use Src\category\application\use_cases\CreateCategoryUseCase;
use Src\category\application\use_cases\DeleteCategoryUseCase;
use Src\category\application\use_cases\UpdateCategoryUseCase;

use Src\category\application\use_cases\FindAllCategoriesUseCase;
use Src\category\application\use_cases\FindBySlugCategoryUseCase;
use Src\category\application\use_cases\FindBySlugWithProductsUseCase;
use Src\category\infrastructure\decorators\CreateCategoryUseCaseEventPublisherDecorator;
use Src\category\infrastructure\decorators\DeleteCategoryUseCaseEventPublisherDecorator;
use Src\category\infrastructure\decorators\UpdateCategoryUseCaseEventPublisherDecorator;
use Src\shared\application\contracts\out\EventPublisher;
use Src\shared\application\contracts\out\ExtractCurrentUser;

class CategoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            CategoryRepository::class,
            EloquentCategoryWriteRepository::class
        );

        $this->app->bind(
            CategoryReadRepository::class,
            EloquentCategoryReadRepository::class
        );

        $this->app->bind(FindAllCategoriesUseCasePort::class, FindAllCategoriesUseCase::class);
        $this->app->bind(FindBySlugCategoryUseCasePort::class, FindBySlugCategoryUseCase::class);
        $this->app->bind(FindBySlugWithProductsUseCasePort::class, FindBySlugWithProductsUseCase::class);

        $this->app->bind(CreateCategoryUseCasePort::class,
            function () {

                return new CreateCategoryUseCaseEventPublisherDecorator(
                    new CreateCategoryUseCase(
                        $this->app->make(CategoryReadRepository::class),
                        $this->app->make(CategoryRepository::class),
                        $this->app->make(ExtractCurrentUser::class)
                    ),
                    $this->app->make(EventPublisher::class),
                );
            }
        );

        $this->app->bind(UpdateCategoryUseCasePort::class,
            function () {

                return new UpdateCategoryUseCaseEventPublisherDecorator(
                    new UpdateCategoryUseCase(
                        $this->app->make(CategoryReadRepository::class),
                        $this->app->make(ExtractCurrentUser::class),
                        $this->app->make(CategoryRepository::class)
                    ),
                    $this->app->make(EventPublisher::class),
                );
            }
        );

        $this->app->bind(DeleteCategoryUseCasePort::class,
            function () {

                return new DeleteCategoryUseCaseEventPublisherDecorator(
                    new DeleteCategoryUseCase(
                        $this->app->make(ExtractCurrentUser::class),
                        $this->app->make(CategoryRepository::class),
                    ),
                    $this->app->make(EventPublisher::class),
                );
            }
        );
    }

    public function boot()
    {
        //
    }
}
