<?php

namespace Src\product\application\use_cases;

use Src\category\application\contracts\out\CategoryReadRepository;
use Src\product\domain\entities\Product;
use Src\product\domain\repositories\ProductRepository;
use Src\shared\domain\value_objects\Id;

class UpdateProductUseCase {

    public function __construct(
        private CategoryReadRepository $categoryReadRepository,
        private ProductRepository $repository
    ) {}

    public function execute(Product $product): Product {
        $this->categoryReadRepository->findById(
            new Id($product->category_id())
        );

        $product = $this->repository->update($product);

        return $product;
    }
}
