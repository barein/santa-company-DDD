<?php

declare(strict_types=1);

namespace App\Shared\Application\Bus;

use Symfony\Component\Messenger\MessageBusInterface;

class EventBus implements EventBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $eventBus,
    ) {
    }

    public function dispatch(object $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
