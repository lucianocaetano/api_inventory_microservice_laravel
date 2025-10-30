<?php

namespace Src\category\infrastructure\decorators;

use Src\category\application\contracts\in\DeleteCategoryUseCasePort;
use Src\shared\application\contracts\out\EventPublisher;

class DeleteCategoryUseCaseEventPublisherDecorator implements DeleteCategoryUseCasePort
{

    public function __construct(
        private DeleteCategoryUseCasePort $deleteCategoryUseCase,
        private EventPublisher $eventPublisher,
    ){}

    public function execute(string $category): void {
        $category = $this->deleteCategoryUseCase->execute($category);

        $this->eventPublisher->publish(
            "category.deleted",
            [
                "slug" => $category,
            ]
        );
    }
}
