<?php

namespace Src\coupon\domain\entities;

use DateTime;
use Src\coupon\domain\exceptions\InvalidCouponAmountException;
use Src\coupon\domain\exceptions\InvalidCouponPercentageException;
use Src\coupon\domain\value_objects\CouponCode;
use Src\coupon\domain\value_objects\CouponType;
use Src\shared\domain\value_objects\Amount;
use Src\shared\domain\value_objects\Id;

/**
 * @package Src\coupon\domain\entities
 *
 * Represents a coupon in the domain
 */
class Coupon {

    public function __construct(
        private CouponCode $code,
        private CouponType $type,
        private Amount|null $amount,
        private int|null $percent,
        private DateTime $expiresAt,
        private bool $isActive,
        private Id|null $category_id = null
    ) {

        if($type->value() === 'fixed' && $amount === null)
            throw new InvalidCouponAmountException("Invalid amount, it is required, it is required if the type is percent");

        if($type->value() === 'percent' && $percent === null)
            throw new InvalidCouponPercentageException("Invalid percentage, it is required if the type is percent");

        if($type->value() === 'percent' && $percent > 90)
            throw new InvalidCouponPercentageException("Invalid percentage: $percent");

        if($type->value() === 'percent') {
            $this->amount = null;
        }

        if($type->value() === 'fixed') {
            $this->percent = null;
        }
    }

    public function code(): string {
        return $this->code->value();
    }

    public function type(): string {
        return $this->type->value();
    }

    public function percent(): int|null {
        return $this->percent;
    }

    public function expiresAt(): string {
        return $this->expiresAt->format('Y-m-d H:i:s');
    }

    public function amount(): ?string {
        if($this->amount === null) return null;

        return $this->amount->toString();
    }

    public function isValidNow(): bool
    {
        return $this->isActive && new DateTime() < $this->expiresAt;
    }

    public function category_id(): ?string {

        if($this->category_id === null) return null;

        return $this->category_id->value();
    }
}
