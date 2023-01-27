<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\EventStoreInterface;
use App\Shared\Domain\Event\StoredEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

class DoctrineEventStore extends ServiceEntityRepository implements EventStoreInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private SerializerInterface $serializer,
    ) {
        parent::__construct($registry, StoredEvent::class);
    }

    public function append(DomainEvent $domainEvent): void
    {
        $storedEvent = new StoredEvent(
            name: $domainEvent->getName(),
            occurredOn: $domainEvent->getOccurredOn(),
            version: $domainEvent->getVersion(),
            body : $this->serializer->serialize($domainEvent, 'json'),
        );

        $this->getEntityManager()->persist($storedEvent);
    }
}
