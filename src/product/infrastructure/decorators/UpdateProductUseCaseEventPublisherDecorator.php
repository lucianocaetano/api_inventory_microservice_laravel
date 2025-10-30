<?php

namespace Src\product\infrastructure\decorators;

use Src\category\application\contracts\out\CategoryReadRepository;
use Src\product\application\contracts\in\UpdateProductUseCasePort;
use Src\product\domain\entities\Product;
use Src\shared\application\contracts\out\EventPublisher;
use Src\shared\domain\value_objects\Id;

class UpdateProductUseCaseEventPublisherDecorator implements UpdateProductUseCasePort
{

    public function __construct(
        private UpdateProductUseCasePort $updateProductUseCase,
        private EventPublisher $eventPublisher,
        private CategoryReadRepository $categoryReadRepository
    ){}

    public function execute(Product $product): Product {
        $product = $this->updateProductUseCase->execute($product);

        $category = $this->categoryReadRepository->findById(new Id($product->category_id()));
        $slug = $category['slug'];

        $this->eventPublisher->publish(
            "product.updated",
            [
                "id" => $product->id(),
                "name" => $product->name(),
                "slug" => $product->slug(),
                "images" => $product->images(),
                "description" => $product->description(),
                "quantity" => $product->quantity(),
                "amount" => $product->price(),
                "currency" => $product->currency_symbol(),
                "category_slug" => $slug
            ]
        );

        return $product;
    }
}
