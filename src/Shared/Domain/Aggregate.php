<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\EventStoreInterface;

abstract class Aggregate
{
    /**
     * @var array<DomainEvent>
     */
    private array $domainEvents = [];

    protected function storeEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    /**
     * @return array<DomainEvent>
     */
    public function getDomainEvents(): array
    {
        return $this->domainEvents;
    }
}
