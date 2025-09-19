<?php

namespace Src\coupon\domain\service;

use Src\shared\domain\exception\InvalidPermission;
use Src\shared\domain\value_objects\Permission;

class CouponService {

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

    public function validCreate(): bool {

        $is_valid = $this->hasPermission(new Permission("create_coupon"));

        if($is_valid)
            throw new InvalidPermission("You don't have permission to create a coupon");

        return true;
    }

    public function validUpdate(): bool {

        $is_valid = $this->hasPermission(new Permission("update_coupon"));

        if($is_valid)
            throw new InvalidPermission("You don't have permission to update a coupon");

        return true;
    }

    public function validDelete(): bool {
        $is_valid = $this->hasPermission(new Permission("delete_coupon"));

        if($is_valid)
            throw new InvalidPermission("You don't have permission to delete a coupon");

        return true;
    }
}
