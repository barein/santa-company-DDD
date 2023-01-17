<?php

namespace App\Contexts\Shared\Domain\Bus;

interface AsyncCommandBusInterface
{
    public function command(object $command): void;
}