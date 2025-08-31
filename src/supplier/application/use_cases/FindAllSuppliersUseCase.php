<?php

namespace Src\supplier\application\use_cases;

use Src\supplier\application\contracts\out\SupplierReadRepository;

class FindAllSuppliersUseCase
{
    public function __construct(
        private SupplierReadRepository $repository
    ) {}

    public function execute(array $filters)
    {
        return $this->repository->findAllSupplier($filters);
    }
}
