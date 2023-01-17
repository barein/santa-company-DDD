<?php

namespace App\Contexts\Shared\Domain\Bus;

interface SyncCommandBusInterface
{
    public function command(object $command): mixed;
}