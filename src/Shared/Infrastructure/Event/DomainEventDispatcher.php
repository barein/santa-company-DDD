<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Application\Bus\EventBusInterface;
use App\Shared\Domain\Event\DispatchedEventsTrackerInterface;
use App\Shared\Domain\Event\EventsToDispatchTrackerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

readonly class DomainEventDispatcher implements MiddlewareInterface
{
    public function __construct(
        private EventsToDispatchTrackerInterface $eventsToDispatchTracker,
        private DispatchedEventsTrackerInterface $dispatchedEventsTracker,
        private EventBusInterface $eventBus,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);

        if ($envelope->last(HandledStamp::class) !== null) {
            foreach ($this->eventsToDispatchTracker->getEventsToDispatch() as $domainEvent) {
                $this->eventBus->dispatch($domainEvent);
                $this->dispatchedEventsTracker->markAsDispatched($domainEvent);
            }

            $this->dispatchedEventsTracker->store();
        }

        return $envelope;
    }
}
