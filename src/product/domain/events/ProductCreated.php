<?php

namespace Src\product\domain\events;

class ProductCreated {

    public function __construct(
        private string $id,
        private string $name,
        private string $slug,
        private string $description,
        private int $amount,
        private string $currency,
        private string $created_at,
        private string $updated_at,
    ) {}

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
