<?php

namespace Src\shared\domain\value_objects;

use InvalidArgumentException;

/**
 * @package Src\category\domain\value_objects
 *
 * Represents a currency in the domain for payments entities
 */
class Currency
{

    /**
     * @param string $code
     * @param string $symbol
     * @param int $decimals (default: 2)
     *
     * @throws CurrencyCodeIsNotSupportedException
     */
    public function __construct(
        private string $code,
        private string $symbol,
        private int $decimals = 2
    ) {
        $code = strtoupper($code);

        if (!in_array($code, ['USD', 'EUR', 'UYU'])) {
            throw new InvalidArgumentException("Currency code is not supported");
        }
    }

    public function code(): string
    {
        return $this->code;
    }

    public function symbol(): string
    {
        return $this->symbol;
    }

    public function decimals(): int
    {
        return $this->decimals;
    }

    public function assertValidAmount(float $value): void
    {
        $decimalsInValue = strlen(substr(strrchr((string)$value, "."), 1) ?? '');
        if ($decimalsInValue > $this->decimals()) {
            throw new InvalidArgumentException("Amount exceeds allowed decimals");
        }
    }

    public function equals(Currency $other): bool
    {
        return $this->code === $other->code;
    }
}
