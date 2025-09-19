<?php

namespace Src\shared\domain\value_objects;

use InvalidArgumentException;

class Amount {

    public function __construct(
        public float $amount,
        public Currency $currency
    ) {

        if($this->amount < 1) {
            throw new InvalidArgumentException("The amount cannot be less than 1");
        }
    }

    public function amount() {

        return $this->amount;
    }

    public function currency() {

        return $this->currency;
    }

    public function editCurrency(Currency $currency) {

        return new self(
            $this->amount,
            $currency
        );
    }

    public function editAmount(float $amount) {

        return new self(
            $amount,
            $this->currency
        );
    }

    public function edit(float $amount, Currency $currency) {

        return new self(
            $amount,
            $currency
        );
    }

    public function toString() {
        return $this->currency->symbol() . $this->amount;
    }
}
