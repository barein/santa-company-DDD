<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Domain\Event\ReceivedEventStoreInterface;
use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\Domain\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @extends ServiceEntityRepository<ReceivedEvent>
 *
 * @method null|ReceivedEvent find($id, $lockMode = null, $lockVersion = null)
 * @method null|ReceivedEvent findOneBy(array $criteria, array $orderBy = null)
 * @method ReceivedEvent[]    findAll()
 * @method ReceivedEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineReceivedEventStore extends ServiceEntityRepository implements ReceivedEventStoreInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly SerializerInterface $serializer,
    ) {
        parent::__construct($registry, ReceivedEvent::class);
    }

    /**
     * @param array<string> $receivingContexts
     */
    public function append(DomainEventInterface $domainEvent, array $receivingContexts): void
    {
        $eventOccurredOn = \DateTimeImmutable::createFromFormat(DomainEventInterface::OCCURRED_ON_FORMAT, $domainEvent->getOccurredOn());

        if (!$eventOccurredOn instanceof \DateTimeImmutable) {
            throw new InvalidArgumentException(sprintf(
                'Domain event occurredOn property should have format %s, %s given.',
                DomainEventInterface::OCCURRED_ON_FORMAT,
                $domainEvent->getOccurredOn(),
            ));
        }

        $receivedEvent = new ReceivedEvent(
            id: new Ulid($domainEvent->getId()),
            name: $domainEvent->getName(),
            emitterContext: $domainEvent::getContext(),
            contexts: $receivingContexts,
            occurredOn: $eventOccurredOn,
            version: $domainEvent::getVersion(),
            body : $this->serializer->serialize($domainEvent, 'json'),
        );

        $this->getEntityManager()->persist($receivedEvent);
    }

    public function get(Ulid $id): ReceivedEvent
    {
        $receivedEvent = $this->find($id);

        if ($receivedEvent === null) {
            throw new NotFoundException(sprintf('ReceivedEvent %s could not be found', $id));
        }

        return $receivedEvent;
    }

    public function store(): void
    {
        $this->getEntityManager()->flush();
    }
}
