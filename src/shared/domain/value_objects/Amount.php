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

        $this->currency->assertValidAmount($this->amount);
    }

    public function amount() {

        return $this->amount;
    }

    public function currency() {

        return $this->currency;
    }

    public function editCurrency(Currency $currency) {

        $currency->assertValidAmount($this->amount);

        return new self(
            $this->amount,
            $currency
        );
    }

    public function editAmount(float $amount) {

        $this->currency->assertValidAmount($amount);

        return new self(
            $amount,
            $this->currency
        );
    }

    public function edit(float $amount, Currency $currency) {

        $currency->assertValidAmount($amount);

        return new self(
            $amount,
            $currency
        );
    }

    public function toString() {
        return $this->amount . ' ' . $this->currency->code();
    }
}
