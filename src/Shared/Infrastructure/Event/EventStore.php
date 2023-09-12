<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Domain\Event\EventStoreInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class EventStore implements EventStoreInterface
{
    /**
     * @param iterable<EventStoreInterface> $eventStores
     */
    public function __construct(
        #[TaggedIterator(tag: EventStoreInterface::class, exclude: self::class)]
        private readonly iterable $eventStores // Todo: delete use of exclude option in Sf 6.3
    ) {
    }

    public function append(DomainEventInterface $domainEvent): void
    {
        foreach ($this->eventStores as $eventStore) {
            $eventStore->append($domainEvent);
        }
    }
}
