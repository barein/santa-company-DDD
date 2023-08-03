<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\CommandBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandBus implements CommandBusInterface
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function command(object $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
