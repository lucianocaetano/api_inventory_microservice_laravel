<?php

namespace Src\product\application\DTOs;

use Spatie\LaravelData\Data;

class ProductDto extends Data
{
    public function __construct(
        public string $name,
        public string $slug,
        public string $description,
        public string $price,
        public string $category_id,
    ) {}
}
