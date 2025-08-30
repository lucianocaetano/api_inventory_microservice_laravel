<?php

namespace Src\product\application\contracts\out;

use Src\shared\domain\value_objects\Id;

interface ProductReadRepository
{
    public function findAllProducts(array $filters): array;
    public function findBySlug(string $slug): array;
    public function findById(Id $id): array;
}
