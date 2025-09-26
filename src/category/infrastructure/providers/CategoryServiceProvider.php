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

        $this->app->bind(CreateCategoryUseCasePort::class, CreateCategoryUseCase::class);
        $this->app->bind(UpdateCategoryUseCasePort::class, UpdateCategoryUseCase::class);
        $this->app->bind(DeleteCategoryUseCasePort::class, DeleteCategoryUseCase::class);
    }

    public function boot()
    {
        //
    }
}
