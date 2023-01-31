<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

interface DispatchedEventsTrackerInterface
{
    public function markAsDispatched(DomainEvent $domainEvent): void;

    public function store(): void;
}
