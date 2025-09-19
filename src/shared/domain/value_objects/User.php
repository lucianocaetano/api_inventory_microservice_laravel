<?php

namespace Src\shared\domain\value_objects;

class User {

    /**
     * $param array<Permission> $permissions
     */
    public function __construct(
        private string $id,
        private string $username,
        private array $permissions,
    ) {}

    public function id(): string {
        return $this->id;
    }

    public function username(): string {
        return $this->username;
    }

    public function permissions(): array {
        return $this->permissions;
    }
}


