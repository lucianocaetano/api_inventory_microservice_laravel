<?php

namespace Src\product\application\use_cases;

use Src\product\application\contracts\out\ProductReadRepository;

class FindAllProductsUseCase {

    public function __construct(
        private ProductReadRepository $repository
    ) {}

    public function execute(array $filters): array {
        return $this->repository->findAllProducts($filters);
    }
}
