<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\ORM;

use App\Shared\Domain\Aggregate;
use App\Shared\Domain\Event\EventStoreInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\RequestStack;

class AggregateHydrationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EventStoreInterface $eventStore,
        private RequestStack $requestStack,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad,
        ];
    }

    public function postLoad(PostLoadEventArgs $args): void
    {
        // No domain event should be triggered from aggregate in a GET request
        if ($this->requestStack->getCurrentRequest()?->getMethod() === 'GET') {
            return;
        }

        $entity = $args->getObject();

        if ($entity instanceof Aggregate) {
            $entity->setEventStore($this->eventStore);
        }
    }
}
