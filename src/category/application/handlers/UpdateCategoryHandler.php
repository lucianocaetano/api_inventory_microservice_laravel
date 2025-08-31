<?php

namespace Src\category\application\handlers;

use Ecotone\Modelling\Attribute\CommandHandler;
use Src\category\application\DTOs\command\UpdateCategoryCommand;
use Src\category\domain\repositories\CategoryRepository;

class DeleteCategoryHandler
{
    public function __construct(
        private CategoryRepository $repository
    ) {}

    #[CommandHandler("inventory_category_channel")]
    public function handle(UpdateCategoryCommand $event)
    {
        $this->repository->update(UpdateCategoryCommand::toModel($event));
    }
}
