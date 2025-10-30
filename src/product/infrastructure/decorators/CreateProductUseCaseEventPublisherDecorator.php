<?php

namespace Src\product\infrastructure\decorators;

use Src\category\application\contracts\out\CategoryReadRepository;
use Src\product\application\contracts\in\CreateProductUseCasePort;
use Src\product\domain\entities\Product;
use Src\shared\application\contracts\out\EventPublisher;
use Src\shared\domain\value_objects\Id;

class CreateProductUseCaseEventPublisherDecorator implements CreateProductUseCasePort
{

    public function __construct(
        private CreateProductUseCasePort $createProductUseCase,
        private EventPublisher $eventPublisher,
        private CategoryReadRepository $categoryReadRepository
    ){}

    public function execute(Product $product): Product {
        $product = $this->createProductUseCase->execute($product);

        $category = $this->categoryReadRepository->findById(new Id($product->category_id()));

        $slug = $category['slug'];

        $this->eventPublisher->publish(
            "product.created",
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
