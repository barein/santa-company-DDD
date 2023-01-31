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
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
        ];
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->persistAggregateDomainEvents($args);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $this->persistAggregateDomainEvents($args);
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
        $this->persistAggregateDomainEvents($args);
    }

    private function persistAggregateDomainEvents(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Aggregate) {
            foreach ($entity->getDomainEvents() as $domainEvent) {
                $this->eventStore->append($domainEvent);
            }
        }
    }
}
