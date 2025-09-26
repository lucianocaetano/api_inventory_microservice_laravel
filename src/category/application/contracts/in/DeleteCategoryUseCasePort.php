<?php

namespace Src\category\application\contracts\in;

interface DeleteCategoryUseCasePort
{
    public function execute(string $category): void;
}
