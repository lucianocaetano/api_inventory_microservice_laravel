<?php

namespace Src\category\application\contracts\in;

interface FindBySlugWithProductsUseCasePort
{
    public function execute(string $slug): array;
}
