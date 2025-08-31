<?php

namespace Src\coupon\domain\entities;

use DateTime;
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

    /**
     * @param CouponCode $code
     * @param CouponType $type
     * @param Amount|null $amount
     * @param int|null $percent
     * @param DateTime $expiresAt
     * @param bool $isActive
     * @param Id|null $category_id
     * @throws InvalidCouponPercentageException
     */
    public function __construct(
        private readonly CouponCode $code,
        private readonly CouponType $type,
        private readonly Amount|null $amount,
        private readonly int|null $percent,
        private readonly DateTime $expiresAt,
        private readonly bool $isActive,
        private readonly Id|null $category_id = null
    ) {

        if($type === 'percent' && $percent > 90) throw new InvalidCouponPercentageException("Invalid percentage: $percent");
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

    public function expiresAt(): DateTime {
        return $this->expiresAt;
    }

    public function amount(): string {
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
