<?php

namespace Src\product\application\contracts\in;

interface FindBySlugProductUseCasePort
{
    public function execute(string $slug): array;
}
