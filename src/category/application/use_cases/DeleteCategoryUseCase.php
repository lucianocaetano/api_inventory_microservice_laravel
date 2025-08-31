<?php

namespace Src\category\application\use_cases;

use Ecotone\Modelling\MessageBus;
use Src\category\application\DTOs\command\DeleteCategoryCommand;

class DeleteCategoryUseCase
{
    public function __construct(
        private MessageBus $messageBus
    ) {}

    public function execute(string $category)
    {

        $this->messageBus->send(
            "inventory_category_channel",
            new DeleteCategoryCommand($category)
        );
    }
}
