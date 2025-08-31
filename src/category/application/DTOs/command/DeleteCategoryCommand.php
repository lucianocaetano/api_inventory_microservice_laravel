<?php

namespace Src\category\application\DTOs\command;

use Spatie\LaravelData\Data;

class DeleteCategoryCommand extends Data
{
    public function __construct(
        private string $slug
    ) {}

    public function getSlug(): string
    {
        return $this->slug;
    }
}
