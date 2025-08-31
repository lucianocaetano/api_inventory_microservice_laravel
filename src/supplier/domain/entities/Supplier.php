<?php

namespace Src\supplier\domain\entities;

use Src\shared\domain\value_objects\Id;

class Supplier
{
    public function __construct(
        private Id $id,
        private string $name,
        private string $email,
        private string $phone,
    ) {}

    public function id(): string
    {
        return $this->id->value();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function phone(): string
    {
        return $this->phone;
    }
}
