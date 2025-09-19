<?php

namespace Src\category\domain\entities;

use Src\shared\domain\value_objects\Id;

/**
 * @package Src\category\domain\entities
 *
 * Represents a category in the domain
 */
class Category {

    /**
     * @param Id $id
     * @param string $slug
     * @param string $name
     * @param string|null $parent
     */
    public function __construct(
        private Id $id,
        private string $slug,
        private string $name,
        private string|null $parent = null,
    ) {}

    public function id(): string {
        return $this->id->value();
    }

    public function slug(): string {
        return $this->slug;
    }

    public function name(): string {
        return $this->name;
    }

    public function parent(): string|null {
        return $this->parent;
    }
}
