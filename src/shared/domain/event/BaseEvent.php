<?php

namespace Src\shared\domain\event;

use Src\shared\domain\value_objects\Id;

/**
 * @package Src\shop\shared\event
 *
 * The objective of this class is to provide an abstract class for other events
 */
abstract class BaseEvent
{

    /**
     * @param string $createdAt It is used to provide an automatic creation date.
     */
    public function __construct(
        private readonly string $eventId = Id::randomId(),
        private readonly string $createdAt = (new \DateTimeImmutable())->format('Y-m-d H:i:s')
    ) {}

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}

