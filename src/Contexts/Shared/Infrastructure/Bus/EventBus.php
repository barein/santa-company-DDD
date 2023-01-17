<?php

namespace App\Contexts\Shared\Infrastructure\Bus;

use App\Contexts\Shared\Domain\Bus\EventBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class EventBus implements EventBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $eventBus,
    ) {
    }

    public function dispatch(object ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}