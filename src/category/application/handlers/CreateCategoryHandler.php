<?php

namespace Src\category\application\handlers;

use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\MessageBus;
use Src\category\application\DTOs\command\CreateCategoryCommand;
use Src\category\application\DTOs\event\CreatedCategoryEvent;
use Src\category\domain\repositories\CategoryRepository;

class CreateCategoryHandler
{
    public function __construct(
        private CategoryRepository $repository,
        private MessageBus $messageBus
    ) {}

    #[CommandHandler("inventory_category_channel")]
    public function handle(CreateCategoryCommand $event)
    {
        $category = $this->repository->save(
            CreateCategoryCommand::toModel($event)
        );

        $this->messageBus->send(
           "created_category",
           CreatedCategoryEvent::fromModel($category)
       );
    }
}
