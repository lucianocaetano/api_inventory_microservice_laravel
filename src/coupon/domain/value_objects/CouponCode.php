<?php

namespace Src\coupon\domain\value_objects;

use Symfony\Component\Uid\Uuid;

class CouponCode {
    private readonly string $code;

    public function __construct(string $code)
    {

        $this->code = strtoupper($code);
    }

    public static function generateCode(): self
    {
        return new self(strtoupper(Uuid::v4()->toRfc4122()));
    }

    public function value(): string
    {
        return $this->code;
    }
}
