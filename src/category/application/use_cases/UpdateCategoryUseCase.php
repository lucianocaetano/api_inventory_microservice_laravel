<?php

namespace Src\category\application\use_cases;

use Src\category\application\contracts\out\CategoryReadRepository;
use Src\category\application\exceptions\ParentCategoryNotFoundException;
use Src\category\domain\entities\Category;
use Src\category\domain\repositories\CategoryRepository;
use Src\shared\infrastructure\exceptions\DataNotFoundException;

class UpdateCategoryUseCase
{
    public function __construct(
        private CategoryRepository $repository,
        private CategoryReadRepository $readRepository
    ) {}

    public function execute(Category $category): Category
    {
        try {
            if($category->parent() != null)
                $this->readRepository->findBySlug($category->parent());
        } catch (DataNotFoundException $e) {
            throw new ParentCategoryNotFoundException('Parent category not found');
        }

        return $this->repository->update($category);
    }
}
