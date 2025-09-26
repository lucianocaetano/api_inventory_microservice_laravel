<?php

namespace Src\category\application\contracts\in;

use Src\product\domain\entities\Product;

interface UpdateProductUseCasePort
{
    public function execute(Product $product): Product;
}
