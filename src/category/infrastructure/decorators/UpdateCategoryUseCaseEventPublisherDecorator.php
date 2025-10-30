<?php

namespace Src\category\infrastructure\decorators;

use Src\category\application\contracts\in\UpdateCategoryUseCasePort;
use Src\category\domain\entities\Category;
use Src\shared\application\contracts\out\EventPublisher;

class UpdateCategoryUseCaseEventPublisherDecorator implements UpdateCategoryUseCasePort
{

    public function __construct(
        private UpdateCategoryUseCasePort $updateCategoryUseCase,
        private EventPublisher $eventPublisher,
    ){}

    public function execute(Category $category): Category {
        $category = $this->updateCategoryUseCase->execute($category);

        $this->eventPublisher->publish(
            "category.updated",
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
