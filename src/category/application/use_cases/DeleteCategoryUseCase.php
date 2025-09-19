<?php

namespace Src\category\application\use_cases;

use Src\category\domain\repositories\CategoryRepository;
use Src\category\domain\services\CategoryService;
use Src\shared\application\contracts\out\ExtractCurrentUser;

class DeleteCategoryUseCase
{
    public function __construct(
        private ExtractCurrentUser $extractCurrentUser,
        private CategoryRepository $repositoryCategory
    ) {}

    public function execute(string $category)
    {

        $user = $this->extractCurrentUser->currentUser();

        $categoryService = new CategoryService(
            $user->permissions(),
        );

        $categoryService->validDelete();

        $this->repositoryCategory->delete($category);
    }
}
