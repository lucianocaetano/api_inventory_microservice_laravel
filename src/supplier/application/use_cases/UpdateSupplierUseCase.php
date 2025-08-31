<?php

namespace Src\supplier\application\use_cases;

use Src\supplier\domain\entities\Supplier;
use Src\supplier\domain\repositories\SupplierRepository;

class UpdateSupplierUseCase
{
    public function __construct(
        private SupplierRepository $repository
    ) {}

    public function execute(Supplier $supplier): Supplier {

        return $this->repository->save($supplier);
    }
}
