<?php

declare(strict_types=1);

namespace App\Shared\Application\Bus;

interface AsyncCommandBusInterface
{
    public function command(object $command): void;
}
