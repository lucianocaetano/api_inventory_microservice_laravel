<?php

namespace Src\category\application\use_cases;

use Src\category\application\contracts\out\CategoryReadRepository;

class FindBySlugWithProductsUseCase
{
    public function __construct(
        private CategoryReadRepository $repository
    ) {}

    public function execute(string $slug): array {
        return $this->repository->findBySlugWithProducts($slug);
    }
}


