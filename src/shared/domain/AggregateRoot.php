<?php

namespace Src\shared\domain;

use Src\shared\domain\event\BaseEvent;

/**
 * @package Src\shop\shared\domain
 *
 * The objective of this class is to provide an abstract class for other aggregate roots so that they can dispatch domain events.
 */
abstract class AggregateRoot
{
    /**
     * @var BaseEvent[]
     */
    private array $recordedEvents = [];

    /**
     * This method is used to register new events
     *
     * @param BaseEvent $event
     * @return void
     */
    protected function recordEvent(BaseEvent $event): void
    {
        $this->recordedEvents[] = $event;
    }

    /**
     * This method is used to register new events
     *
     * @return BaseEvent[]
     */
    public function pullDomainEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }
}

