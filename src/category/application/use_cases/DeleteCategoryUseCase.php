<?php

namespace Src\category\application\use_cases;

use Src\category\domain\repositories\CategoryRepository;

class DeleteCategoryUseCase
{
    public function __construct(
        private CategoryRepository $repository
    ) {}

    public function execute(string $category)
    {

        $this->repository->delete($category);
    }
}
