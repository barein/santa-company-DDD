<?php

declare(strict_types=1);

namespace App\Shared\Application\Bus;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class SyncCommandBus implements SyncCommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function command(object $command): mixed
    {
        return $this->handle($command);
    }
}
