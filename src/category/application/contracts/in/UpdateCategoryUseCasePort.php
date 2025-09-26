<?php

namespace Src\category\application\contracts\in;

use Src\category\domain\entities\Category;

interface UpdateCategoryUseCasePort
{
    public function execute(Category $category): Category;
}
