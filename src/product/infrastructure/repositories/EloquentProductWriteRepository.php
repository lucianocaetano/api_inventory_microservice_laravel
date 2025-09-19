<?php

namespace Src\product\infrastructure\repositories;

use Src\product\domain\entities\Product;
use Src\product\domain\repositories\ProductRepository;
use Src\product\infrastructure\models\Product as ModelsProduct;
use Src\shared\domain\value_objects\Id;
use Src\shared\infrastructure\exceptions\DataNotFoundException;

class EloquentProductWriteRepository implements ProductRepository {

    public function save(Product $product): Product
    {

        ModelsProduct::create([
            'id' => $product->id(),
            'slug' => $product->slug(),
            'name' => $product->name(),
            'description' => $product->description(),
            'quantity' => $product->quantity(),
            'currency_symbol' => $product->currency_symbol(),
            'price' => $product->price(),
            'category_id' => $product->category_id(),
        ]);

        return $product;
    }

    public function update(Product $product): Product
    {
        $model = ModelsProduct::where('id', $product->id())->first();

        if(!$model)
            throw new DataNotFoundException('Product');

        $model->update([
            'slug' => $product->slug(),
            'name' => $product->name(),
            'description' => $product->description(),
            'quantity' => $product->quantity(),
            'currency_symbol' => $product->currency_symbol(),
            'price' => $product->price(),
            'category_id' => $product->category_id(),
        ]);

        return $product;
    }

    public function delete(Id $id)
    {
        $model = ModelsProduct::where('id', $id->value())->first();

        if(!$model)
            throw new DataNotFoundException('Product');

        $model->delete();
    }
}
