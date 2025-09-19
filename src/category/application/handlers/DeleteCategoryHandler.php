<?php

namespace Src\category\application\handlers;

use Ecotone\Modelling\Attribute\CommandHandler;
use Src\category\application\DTOs\command\DeleteCategoryCommand;
use Src\category\domain\repositories\CategoryRepository;

class DeleteCategoryHandler
{
    public function __construct(
        private CategoryRepository $repository,
    ) {}

    #[CommandHandler("inventory_delete_category_channel")]
    public function handle(DeleteCategoryCommand $event)
    {
        $this->repository->delete($event->getSlug());
    }
}
