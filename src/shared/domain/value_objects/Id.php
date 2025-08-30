<?php

namespace Src\shared\domain\value_objects;

use InvalidArgumentException;

/**
 * @package Src\category\domain\value_objects
 *
 * Represents a unique identifier
 */
class Id
{
    private readonly string $value;

    /**
     * @param string $value The raw ID string value.
     * @throws InvalidArgumentException If the ID is empty.
     */
    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException("The ID value cannot be empty.");
        }
        $this->value = $value;
    }

    /**
     * @return string returns the id or internal value
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * It is for the case in which the database cannot be used

     * @return Id returns a random id
     */
    public static function randomId (): self {
        $uuid = uniqid();
        return new self($uuid);
    }

    /**
     * It is to compare different Id objects by their state value.
     *
     * @param Id $other
     * @return bool
     */
    public function equals(Id $other): bool
    {
        return $this->value === $other->value();
    }
}
