<?php

namespace Src\product\infrastructure\decorators;

use Src\product\application\contracts\in\DeleteProductUseCasePort;
use Src\shared\application\contracts\out\EventPublisher;
use Src\shared\domain\value_objects\Id;

class DeleteProductUseCaseEventPublisherDecorator implements DeleteProductUseCasePort
{

    public function __construct(
        private DeleteProductUseCasePort $deleteProductUseCase,
        private EventPublisher $eventPublisher
    ){}

    public function execute(Id $id): void {
        $this->deleteProductUseCase->execute($id);

        $this->eventPublisher->publish(
            "product.deleted",
            [
                "id" => $id->value(),
            ]
        );
    }
}
