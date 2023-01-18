<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus;

interface AsyncCommandBusInterface
{
    public function command(object $command): void;
}
