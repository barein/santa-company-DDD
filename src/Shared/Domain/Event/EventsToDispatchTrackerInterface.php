<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

interface EventsToDispatchTrackerInterface
{
    /**
     * @return array<DomainEventInterface>
     */
    public function getEventsToDispatch(): array;
}
