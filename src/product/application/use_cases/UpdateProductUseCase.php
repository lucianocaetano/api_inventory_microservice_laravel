<?php

namespace Src\product\application\use_cases;

use Src\category\application\contracts\in\UpdateProductUseCasePort;
use Src\category\application\contracts\out\CategoryReadRepository;
use Src\product\domain\entities\Product;
use Src\product\domain\repositories\ProductRepository;
use Src\product\domain\service\ProductService;
use Src\shared\application\contracts\out\ExtractCurrentUser;
use Src\shared\domain\value_objects\Id;

class UpdateProductUseCase implements UpdateProductUseCasePort {

    public function __construct(
        private CategoryReadRepository $categoryReadRepository,
        private ProductRepository $repository,
        private ExtractCurrentUser $extractCurrentUser
    ) {}

    public function execute(Product $product): Product {

        $user = $this->extractCurrentUser->currentUser();

        $service = new ProductService($user->permissions());

        $service->validUpdate();

        $this->categoryReadRepository->findById(
            new Id($product->category_id())
        );

        $product = $this->repository->update($product);

        return $product;
    }
}
