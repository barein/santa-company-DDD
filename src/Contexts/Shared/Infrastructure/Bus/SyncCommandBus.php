<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Bus;

use App\Contexts\Shared\Domain\Bus\SyncCommandBusInterface;
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
