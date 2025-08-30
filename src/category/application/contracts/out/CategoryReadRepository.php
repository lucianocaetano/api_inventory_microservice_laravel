<?php

namespace Src\category\application\contracts\out;

use Src\shared\domain\value_objects\Id;

interface CategoryReadRepository
{
    public function findAll(array $filter): array;

    public function findById(Id $id): array;

    public function findBySlug(string $slug): array;

    public function findBySlugWithProducts(string $category_slug): array;
}
