<?php

namespace Src\category\application\contracts\in;

interface FindAllCategoriesUseCasePort
{
    public function execute(array $filter): array;
}
