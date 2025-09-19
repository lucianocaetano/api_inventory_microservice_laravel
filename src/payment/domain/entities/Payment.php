<?php

namespace Src\payment\domain\entities;

use Src\shared\domain\value_objects\Amount;
use Src\shared\domain\value_objects\Id;

class Payment {

    public function __construct(
        private Id $id,
        private Id $order_id,
        private Id $client_id,
        private string $status,
        private Amount $amount
    ) {}

    public function id(): string {
        return $this->id->value();
    }

    public function order_id(): string {
        return $this->order_id->value();
    }

    public function amount(): string {
        return $this->amount->toString();
    }

    public function client_id(): string {
        return $this->client_id->value();
    }

    public function status(): string {
        return $this->status;
    }
}
