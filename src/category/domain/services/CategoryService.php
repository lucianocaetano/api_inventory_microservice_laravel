<?php

namespace Src\category\domain\services;

use Src\shared\domain\exception\InvalidPermission;
use Src\shared\domain\value_objects\Permission;

class CategoryService
{

    /**
     * @param array<Permission> $permissions
     */
    public function __construct(
        private array $permissions
    ) {}

    private function hasPermission(Permission $currentPermission): bool
    {
        return !in_array($currentPermission, $this->permissions);
    }

    public function validCreate(): bool
    {
        $is_valid = $this->hasPermission(new Permission("create_category"));

        if($is_valid)
            throw new InvalidPermission("You don't have permission to create a category");

        return $is_valid;
    }

    public function validUpdate(): bool
    {
        $is_valid = $this->hasPermission(new Permission("update_category"));

        if($is_valid)
            throw new InvalidPermission("You don't have permission to update a category");

        return $is_valid;
    }

    public function validDelete(): bool
    {
        $is_valid = $this->hasPermission(new Permission("delete_category"));

        if($is_valid)
            throw new InvalidPermission("You don't have permission to delete a category");

        return $is_valid;
    }
}
