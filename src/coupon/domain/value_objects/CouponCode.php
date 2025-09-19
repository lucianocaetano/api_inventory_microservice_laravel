<?php

namespace Src\coupon\domain\value_objects;

use Illuminate\Support\Str;

class CouponCode {
    private readonly string $code;

    public function __construct(string $code)
    {
        $this->code = strtoupper($code);
    }

    public static function generateCode(): self
    {
        return new self(Str::random(10));
    }

    public function value(): string
    {
        return $this->code;
    }
}
