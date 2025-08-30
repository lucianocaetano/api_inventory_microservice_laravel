<?php

namespace Src\product\application\use_cases;

use Src\product\application\contracts\out\ProductReadRepository;

class FindBySlugProductUseCase
{

    public function __construct(
        private ProductReadRepository $repository
    ) {}

    public function execute(string $slug): array {

        $data = $this->repository->findBySlug($slug);

        return $data;
    }
}
