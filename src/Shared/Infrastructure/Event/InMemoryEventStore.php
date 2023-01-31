<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\EventsToDispatchTrackerInterface;
use App\Shared\Domain\Event\EventStoreInterface;

class InMemoryEventStore implements EventStoreInterface, EventsToDispatchTrackerInterface
{
    /**
     * @var array<DomainEvent>
     */
    private array $domainEvents = [];

    public function append(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    public function getEventsToDispatch(): array
    {
        $eventsToDispatch = $this->domainEvents;
        $this->domainEvents = [];

        return $eventsToDispatch;
    }
}
