<?php

namespace Src\shared\domain\value_objects;

use Src\role\domain\exception\InvalidPermissionName;

class Permission {

    public function isValid(): bool {

        $permissions = [
            "create_role",
            "update_role",
            "delete_role",
            "read_role",

            "update_user",
            "delete_user",
            "read_user",

            "create_category",
            "update_category",
            "delete_category",

            "create_product",
            "update_product",
            "delete_product",

            "create_coupon",
            "update_coupon",
            "delete_coupon",

            "read_payment"
        ];

        return in_array($this->name, $permissions);
    }

    public function __construct(
        private string $name,
    ) {

        if(!$this->isValid())
            throw new InvalidPermissionName("Invalid permission name: {$this->name}");
    }

    public function name(): string {
        return $this->name;
    }

    public function equals(Permission $permission): bool {
        return $this->name() == $permission->name();
    }
}
