<?php

namespace Src\product\application\use_cases;

use Src\product\domain\repositories\ProductRepository;
use Src\product\domain\service\ProductService;
use Src\shared\application\contracts\out\ExtractCurrentUser;
use Src\shared\domain\value_objects\Id;

class DeleteProductUseCase {

    public function __construct(
        private ProductRepository $repository,
        private ExtractCurrentUser $extractCurrentUser
    ) {}

    public function execute(Id $id) {

        $user = $this->extractCurrentUser->currentUser();

        $service = new ProductService($user->permissions());

        $service->validDelete();

        $this->repository->delete($id);
    }
}
