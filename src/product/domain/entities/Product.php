<?php

namespace Src\product\domain\entities;

use Src\shared\domain\value_objects\Amount;
use Src\shared\domain\value_objects\Id;

/**
 * @package Src\product\domain\entities
 *
 * Represents a product in the domain
 */
class Product {

    /**
     * @param Id $id
     * @param string $slug
     * @param string $name
     * @param string $description
     * @param int $quantity
     * @param Amount $price
     * @param Id $category_id
     */
    public function __construct(
        private Id $id,
        private string $slug,
        private string $name,
        private string $description,
        private int $quantity,
        private Amount $price,
        private Id $category_id,
    ) {}

    /**
     * @return string
     */
    public function id(): string {

        return $this->id->value();
    }

    /**
     * @return string
     */
    public function name(): string {

        return $this->name;
    }

    /**
     * @return string
     */
    public function description(): string {

        return $this->description;
    }

    /**
     * @return int
     */
    public function quantity(): int {

        return $this->quantity;
    }

    /**
     * @return float
     */
    public function price(): float {

        return $this->price->amount();
    }

    /**
     * @return string
     */
    public function category_id(): string {

        return $this->category_id->value();
    }

    public function slug(): string {
        return $this->slug;
    }

    public function currency_symbol(): string {
        return $this->price->currency->symbol();
    }

    public function priceToString(): string {
        return $this->price->toString();
    }
}

