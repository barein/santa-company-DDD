<?php

namespace App\Contexts\Shared\Domain\Bus;

interface EventBusInterface
{
    public function dispatch(object ...$event): void;
}