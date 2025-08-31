<?php

namespace Src\supplier\application\use_cases;

use Src\shared\domain\value_objects\Id;
use Src\supplier\domain\repositories\SupplierRepository;

class DeleteSupplierUseCase
{
    public function __construct(
        private SupplierRepository $repository
    ) {}

    public function execute(Id $id)
    {
        return $this->repository->delete($id);
    }
}
