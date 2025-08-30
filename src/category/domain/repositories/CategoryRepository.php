<?php

namespace Src\category\domain\repositories;

use Src\category\domain\entities\Category;

interface CategoryRepository {

    public function save(Category $category): Category;
    public function update(Category $category): Category;
    public function delete(string $category_slug);
}
