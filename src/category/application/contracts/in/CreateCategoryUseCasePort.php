<?php

namespace Src\category\application\contracts\in;

use Src\category\domain\entities\Category;

interface CreateCategoryUseCasePort
{
    public function execute(Category $category): Category;
}
