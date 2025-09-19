<?php

namespace Src\category\application\use_cases;

use Src\category\application\contracts\out\CategoryReadRepository;
use Src\category\application\exceptions\ParentCategoryNotFoundException;
use Src\category\domain\entities\Category;
use Src\category\domain\repositories\CategoryRepository;
use Src\category\domain\services\CategoryService;
use Src\shared\application\contracts\out\ExtractCurrentUser;
use Src\shared\infrastructure\exceptions\DataNotFoundException;

class CreateCategoryUseCase
{
    public function __construct(
        private CategoryReadRepository $readRepository,
        private CategoryRepository $categoryRepository,
        private ExtractCurrentUser $extractCurrentUser,
    ) {}

    public function execute(Category $category): Category
    {
        try {
            if($category->parent() != null)
                $this->readRepository->findBySlug($category->parent());
        } catch (DataNotFoundException $_) {
            throw new ParentCategoryNotFoundException('Parent category not found');
        }

        $user = $this->extractCurrentUser->currentUser();

        $categoryService = new CategoryService(
            $user->permissions(),
        );

        $categoryService->validCreate();

        $this->categoryRepository->save(
            $category
        );

        return $category;
    }
}
