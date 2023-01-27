<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\ORM;

use App\Shared\Domain\Aggregate;
use App\Shared\Domain\Event\EventStoreInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

class AggregatePersistanceSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EventStoreInterface $eventStore,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Aggregate) {
            foreach ($entity->getEvents() as $domainEvent) {
                $this->eventStore->append($domainEvent);
            }
        }
    }
}
