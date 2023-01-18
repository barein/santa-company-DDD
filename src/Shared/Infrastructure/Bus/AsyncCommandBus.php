<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Bus\AsyncCommandBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class AsyncCommandBus implements AsyncCommandBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    public function command(object $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
