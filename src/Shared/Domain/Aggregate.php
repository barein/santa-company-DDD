<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Event\DomainEvent;

abstract class Aggregate
{
    /**
     * @var array<DomainEvent>
     */
    private array $domainEvents = [];

    protected function raiseEvent(DomainEvent $domainEvent): void
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
