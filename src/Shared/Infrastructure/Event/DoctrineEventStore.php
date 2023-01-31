<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\DispatchedEventsTrackerInterface;
use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\EventStoreInterface;
use App\Shared\Domain\Event\StoredEvent;
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

    public function append(DomainEvent $domainEvent): void
    {
        $eventOccurredOn = \DateTimeImmutable::createFromFormat(DomainEvent::OCCURRED_ON_FORMAT, $domainEvent->getOccurredOn());

        if (!$eventOccurredOn instanceof \DateTimeImmutable) {
            throw new InvalidArgumentException(sprintf(
                'Domain event occurredOn property should have format %s, %s given.',
                DomainEvent::OCCURRED_ON_FORMAT,
                $domainEvent->getOccurredOn(),
            ));
        }

        $storedEvent = new StoredEvent(
            ulid: new Ulid($domainEvent->getUlid()),
            name: $domainEvent->getName(),
            context: $domainEvent->getContext(),
            occurredOn: $eventOccurredOn,
            version: $domainEvent->getVersion(),
            body : $this->serializer->serialize($domainEvent, 'json'),
        );

        $this->getEntityManager()->persist($storedEvent);
    }

    public function markAsDispatched(DomainEvent $domainEvent): void
    {
        $storedEvent = $this->findOneBy(['ulid' => $domainEvent->getUlid()]);

        if (!$storedEvent instanceof StoredEvent) {
            throw new NotFoundException(sprintf(
                'StoredEvent %s could not be found',
                $domainEvent->getUlid(),
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
