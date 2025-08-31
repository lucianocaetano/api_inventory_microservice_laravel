<?php

namespace Src\category\application\DTOs\event;

use Spatie\LaravelData\Data;
use Src\category\domain\entities\Category;
use Src\shared\domain\value_objects\Id;

class CreatedCategoryEvent extends Data
{
    public function __construct(
        private string $id,
        private string $name,
        private string|null $parent
    ) {}

    public static function fromModel(Category $model): self
    {
        return new self($model->id(), $model->name(), $model->parent());
    }

    public static function toModel(CreatedCategoryEvent $event): Category
    {
        return new Category(
            new Id($event->id),
            $event->name,
            $event->parent
        );
    }
}
