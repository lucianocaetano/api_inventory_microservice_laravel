<?php

namespace Src\category\application\contracts\in;

interface FindBySlugProductUseCasePort
{
    public function execute(string $slug): array;
}
