<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

abstract class AggregateRoot
{
    /**
     * @var array<DomainEvent>
     */
    private array $domainEvents = [];

    private EventStoreInterface $eventStore;

    protected function raiseEvent(DomainEvent $domainEvent): void
    {
        if (isset($this->eventStore)) {
            $this->eventStore->append($domainEvent);

            return;
        }

        $this->domainEvents[] = $domainEvent;
    }

    /**
     * @return array<DomainEvent>
     */
    public function getDomainEvents(): array
    {
        return $this->domainEvents;
    }

    public function setEventStore(EventStoreInterface $eventStore): void
    {
        $this->eventStore = $eventStore;
    }
}
