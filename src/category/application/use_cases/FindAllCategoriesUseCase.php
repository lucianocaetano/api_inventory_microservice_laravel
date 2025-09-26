<?php

namespace Src\category\application\use_cases;

use Src\category\application\contracts\in\FindAllCategoriesUseCasePort;
use Src\category\application\contracts\out\CategoryReadRepository;

class FindAllCategoriesUseCase implements FindAllCategoriesUseCasePort{

    public function __construct(
        private CategoryReadRepository $repository
    ) {}

    public function execute(array $filter): array {
        return $this->repository->findAll($filter);
    }
}
