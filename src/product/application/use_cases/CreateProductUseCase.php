<?php

namespace Src\product\application\use_cases;

use Src\category\application\contracts\out\CategoryReadRepository;
use Src\product\application\contracts\in\CreateProductUseCasePort;
use Src\product\domain\entities\Product;
use Src\product\domain\repositories\ProductRepository;
use Src\product\domain\service\ProductService;
use Src\shared\application\contracts\out\ExtractCurrentUser;
use Src\shared\domain\value_objects\Id;

class CreateProductUseCase implements CreateProductUseCasePort {

    public function __construct(
        private ProductRepository $repository,
        private CategoryReadRepository $categoryReadRepository,
        private ExtractCurrentUser $extractCurrentUser,
    ) {}

    public function execute(Product $product): Product {
        $this->categoryReadRepository->findById(
            new Id($product->category_id())
        );

        $user = $this->extractCurrentUser->currentUser();

        $service = new ProductService($user->permissions());

        $service->validCreate();

        $product = $this->repository->save($product);

        return $product;
    }
}
