<?php

namespace Src\category\infrastructure\decorators;

use Src\category\application\contracts\in\CreateCategoryUseCasePort;
use Src\category\domain\entities\Category;
use Src\shared\application\contracts\out\EventPublisher;

class CreateCategoryUseCaseEventPublisherDecorator implements CreateCategoryUseCasePort
{

    public function __construct(
        private CreateCategoryUseCasePort $createCategoryUseCase,
        private EventPublisher $eventPublisher,
    ){}

    public function execute(Category $category): Category {
        $category = $this->createCategoryUseCase->execute($category);

        $this->eventPublisher->publish(
            "category.created",
            [
                "id" => $category->id(),
                "name" => $category->name(),
                "slug" => $category->slug(),
                "parent" => $category->parent(),
            ]
        );

        return $category;
    }
}
