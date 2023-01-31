<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\EventStoreInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class EventStore implements EventStoreInterface
{
    /**
     * @var iterable<EventStoreInterface>
     */
    private iterable $eventStores;

    /**
     * @param iterable<EventStoreInterface> $eventStores
     */
    public function __construct(
        #[TaggedIterator(tag: EventStoreInterface::class, exclude: self::class)] iterable $eventStores,
    ) {
        $this->eventStores = $eventStores;
    }

    public function append(DomainEvent $domainEvent): void
    {
        foreach ($this->eventStores as $eventStore) {
            $eventStore->append($domainEvent);
        }
    }
}
