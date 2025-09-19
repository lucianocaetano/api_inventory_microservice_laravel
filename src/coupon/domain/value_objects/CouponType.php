<?php

namespace Src\coupon\domain\value_objects;

use Src\coupon\domain\exceptions\InvalidCouponTypeException;

class CouponType
{
    public const PERCENT = 'percent';
    public const FIXED = 'fixed';

    public function __construct(
        private readonly string $value
    ) {

        if($value !== self::PERCENT && $value !== self::FIXED) throw new InvalidCouponTypeException("Invalid coupon type: $value");
    }

    public function value() {
        return $this->value;
    }
}
