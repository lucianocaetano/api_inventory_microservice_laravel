<?php

namespace Src\shared\domain\value_objects;

/**
 * @package Src\category\domain\value_objects
 *
 * Represents a currency in the domain for payments entities
 */
class Currency
{

    /**
     * @param string $symbol
     */
    public function __construct(
        private string $symbol,
    ) {}

    public function symbol(): string
    {
        return $this->symbol;
    }

    public function equals(Currency $other): bool
    {
        return $this->symbol === $other->symbol;
    }
}
