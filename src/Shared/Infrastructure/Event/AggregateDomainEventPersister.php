<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\AggregateRoot;
use App\Shared\Domain\Event\EventStoreInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class AggregateDomainEventPersister implements EventSubscriberInterface
{
    public function __construct(
        private EventStoreInterface $eventStore,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
            Events::prePersist,
            Events::preRemove,
        ];
    }

    /**
     * Already persisted aggregate root will use eventStore directly to dispatch event related to operation made on it or on child entities (except for root deletion)
     */
    public function postLoad(PostLoadEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof AggregateRoot) {
            $entity->setEventStore($this->eventStore);
        }
    }

    /**
     * Persisting domain events here only work for Aggregate root creation, not child entities creation.
     * Hence, EventStore is injected at aggregate root postLoad.
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->storeAggregateDomainEvents($args);
    }

    /**
     * Only for aggregate root deletion
     */
    public function preRemove(PreRemoveEventArgs $args): void
    {
        $this->storeAggregateDomainEvents($args);
    }

    private function storeAggregateDomainEvents(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof AggregateRoot) {
            foreach ($entity->getDomainEvents() as $domainEvent) {
                $this->eventStore->append($domainEvent);
            }
        }
    }
}
