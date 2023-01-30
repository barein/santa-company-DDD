<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Aggregate;
use App\Shared\Domain\Event\EventStoreInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class AggregateDomainEventPersister implements EventSubscriberInterface
{
    public function __construct(
        private EventStoreInterface $eventStore,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
        ];
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Aggregate) {
            $this->persistAggregateDomainEvents($entity);
        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Aggregate) {
            $this->persistAggregateDomainEvents($entity);
        }
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Aggregate) {
            $this->persistAggregateDomainEvents($entity);
        }
    }

    private function persistAggregateDomainEvents(Aggregate $entity): void
    {
        foreach ($entity->getDomainEvents() as $domainEvent) {
            $this->eventStore->append($domainEvent);
        }
    }
}
