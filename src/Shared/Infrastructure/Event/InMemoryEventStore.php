<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Domain\Event\EventsToDispatchTrackerInterface;
use App\Shared\Domain\Event\EventStoreInterface;

class InMemoryEventStore implements EventStoreInterface, EventsToDispatchTrackerInterface
{
    /**
     * @var array<DomainEventInterface>
     */
    private array $domainEvents = [];

    public function append(DomainEventInterface $domainEvent): void
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
