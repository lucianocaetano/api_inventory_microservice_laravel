<?php

namespace Src\product\infrastructure\repositories;

use Src\product\application\contracts\out\ProductReadRepository;
use Src\product\infrastructure\models\Product;
use Src\shared\domain\value_objects\Id;
use Src\shared\infrastructure\exceptions\DataNotFoundException;

class EloquentProductReadRepository implements ProductReadRepository
{
    public function findAllProducts(array $filters): array
    {
        $data = Product::paginate();

        return [
            'items' => $data->items(),
            'meta' => [
                "total" => $data->total(),
                "current_page" => $data->currentPage(),
                "last_page" => $data->lastPage()
            ],

        ];
    }

    public function findBySlug(string $slug): array
    {
        $product = Product::where('slug', $slug)->first();

        if(!$product)
            throw new DataNotFoundException('Product');

        return $product->toArray();
    }

    public function findById(Id $id): array
    {
        $product = Product::where('id', $id->value())->first();

        if(!$product)
            throw new DataNotFoundException('Product');

        return $product->toArray();
    }
}
