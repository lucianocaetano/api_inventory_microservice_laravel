<?php

namespace Src\shared\domain\value_objects;

use Src\shared\domain\exception\InvalidPermission;

class Permission {

    public function isValid(): bool {

        $permissions = [
            "update_provider",
            "delete_provider",
            "create_provider",
            "read_provider",

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
            throw new InvalidPermission("Invalid permission name: {$this->name}");
    }

    public function name(): string {
        return $this->name;
    }

    public function equals(Permission $permission): bool {
        return $this->name() == $permission->name();
    }
}
