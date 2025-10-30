<?php

namespace Src\product\application\contracts\in;

use Src\product\domain\entities\Product;

interface CreateProductUseCasePort
{
    public function execute(Product $category): Product;
}
