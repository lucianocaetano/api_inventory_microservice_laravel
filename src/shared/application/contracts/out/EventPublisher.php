<?php

namespace Src\shared\application\contracts\out;

interface EventPublisher
{
    public function publish(string $event, array $payload): void;
}
