<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

interface DispatchedEventsTrackerInterface
{
    public function markAsDispatched(DomainEventInterface $domainEvent): void;

    public function store(): void;
}
