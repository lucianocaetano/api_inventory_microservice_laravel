<?php

namespace Src\product\domain\repositories;

use Src\product\domain\entities\Product;
use Src\shared\domain\value_objects\Id;

interface ProductRepository {

    public function save(Product $product): Product;
    public function update(Product $product): Product;
    public function delete(Id $id);
}
