<?php

namespace Src\category\application\use_cases;

use Ecotone\Modelling\MessageBus;

use Src\category\application\contracts\out\CategoryReadRepository;
use Src\category\application\DTOs\command\CreateCategoryCommand;
use Src\category\application\exceptions\ParentCategoryNotFoundException;
use Src\category\domain\entities\Category;

use Src\shared\infrastructure\exceptions\DataNotFoundException;

class CreateCategoryUseCase
{
    public function __construct(
        private CategoryReadRepository $readRepository,
        private MessageBus $messageBus
    ) {}

    public function execute(Category $category): Category
    {
        try {
            if($category->parent() != null)
                $this->readRepository->findBySlug($category->parent());


        } catch (DataNotFoundException $e) {
            throw new ParentCategoryNotFoundException('Parent category not found');
        }

        $this->messageBus->send(
            "inventory_category_channel",
            CreateCategoryCommand::fromModel($category)
        );

        return $category;
    }
}
