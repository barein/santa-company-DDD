<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\EventStoreInterface;

abstract class Aggregate
{
    /**
     * @var array<DomainEvent>
     */
    private array $domainEvents = [];

    protected EventStoreInterface $eventStore;

    public function setEventStore(EventStoreInterface $eventStore): void
    {
        $this->eventStore = $eventStore;
    }

    protected function storeEvent(DomainEvent $domainEvent): void
    {
        if (isset($this->eventStore)) {
            $this->eventStore->append($domainEvent);
        }

        $this->domainEvents[] = $domainEvent;
    }

    /**
     * @return array<DomainEvent>
     */
    public function getEvents(): array
    {
        return $this->domainEvents;
    }
}
