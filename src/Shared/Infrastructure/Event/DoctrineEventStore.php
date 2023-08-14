<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\DispatchedEventsTrackerInterface;
use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Domain\Event\EventStoreInterface;
use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\Domain\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Ulid;

class DoctrineEventStore extends ServiceEntityRepository implements EventStoreInterface, DispatchedEventsTrackerInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private SerializerInterface $serializer,
    ) {
        parent::__construct($registry, StoredEvent::class);
    }

    public function append(DomainEventInterface $domainEvent): void
    {
        $eventOccurredOn = \DateTimeImmutable::createFromFormat(DomainEventInterface::OCCURRED_ON_FORMAT, $domainEvent->getOccurredOn());

        if (!$eventOccurredOn instanceof \DateTimeImmutable) {
            throw new InvalidArgumentException(sprintf(
                'Domain event occurredOn property should have format %s, %s given.',
                DomainEventInterface::OCCURRED_ON_FORMAT,
                $domainEvent->getOccurredOn(),
            ));
        }

        $storedEvent = new StoredEvent(
            id: new Ulid($domainEvent->getId()),
            name: $domainEvent->getName(),
            context: $domainEvent->getContext(),
            occurredOn: $eventOccurredOn,
            version: $domainEvent->getVersion(),
            body : $this->serializer->serialize($domainEvent, 'json'),
        );

        $this->getEntityManager()->persist($storedEvent);
    }

    public function markAsDispatched(DomainEventInterface $domainEvent): void
    {
        $storedEvent = $this->find($domainEvent->getId());

        if (!$storedEvent instanceof StoredEvent) {
            throw new NotFoundException(sprintf(
                'StoredEvent %s could not be found',
                $domainEvent->getId(),
            ));
        }

        $storedEvent->markAsDispatched();
    }

    public function store(): void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }
}
