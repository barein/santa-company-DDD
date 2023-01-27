<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

interface EventStoreInterface
{
    public function append(DomainEvent $domainEvent): void;
}
