<?php

namespace Src\category\infrastructure\providers;

use Illuminate\Support\ServiceProvider;

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
use Src\role\domain\entities\ExtractCurrentUser;

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

        $this->app->bind(FindAllCategoriesUseCase::class);
        $this->app->bind(FindBySlugCategoryUseCase::class);
        $this->app->bind(FindBySlugWithProductsUseCase::class);

        $this->app->bind(CreateCategoryUseCase::class);
        $this->app->bind(UpdateCategoryUseCase::class);
        $this->app->bind(DeleteCategoryUseCase::class);

        $this->app->bind(ExtractCurrentUser::class);
    }

    public function boot()
    {
        //
    }
}
