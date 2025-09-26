<?php

namespace Src\category\application\contracts\in;

interface FindBySlugCategoryUseCasePort
{
    public function execute(string $slug): array;
}
