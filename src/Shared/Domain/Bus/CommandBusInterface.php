<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus;

interface CommandBusInterface
{
    public function command(object $command): void;
}
