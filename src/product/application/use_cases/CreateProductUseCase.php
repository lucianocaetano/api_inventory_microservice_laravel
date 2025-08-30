<?php

namespace Src\product\application\use_cases;

use Src\category\application\contracts\out\CategoryReadRepository;

use Src\product\domain\entities\Product;
use Src\product\domain\repositories\ProductRepository;
use Src\shared\domain\value_objects\Id;

class CreateProductUseCase {

    public function __construct(
        private ProductRepository $repository,
        private CategoryReadRepository $categoryReadRepository
    ) {}

    public function execute(Product $product): Product {
        $this->categoryReadRepository->findById(
            new Id($product->category_id())
        );

        $product = $this->repository->save($product);

        return $product;
    }
}
