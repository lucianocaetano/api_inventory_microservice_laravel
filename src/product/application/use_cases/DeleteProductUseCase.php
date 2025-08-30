<?php

namespace Src\product\application\use_cases;

use Src\product\domain\repositories\ProductRepository;
use Src\shared\domain\value_objects\Id;

class DeleteProductUseCase {

    public function __construct(
        private ProductRepository $repository
    ) {}

    public function execute(Id $id) {
        $this->repository->delete($id);
    }
}
