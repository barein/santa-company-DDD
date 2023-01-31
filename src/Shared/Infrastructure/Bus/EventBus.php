<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Bus\EventBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

class EventBus implements EventBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $eventBus,
    ) {
    }

    public function dispatch(object $event, bool $afterCurrentBus = true): void
    {
        $stamps = [];
        if ($afterCurrentBus) {
            $stamps[] = new DispatchAfterCurrentBusStamp();
        }

        $this->eventBus->dispatch($event, $stamps);
    }
}
