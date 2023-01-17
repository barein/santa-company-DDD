<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Domain\Bus;

interface EventBusInterface
{
    public function dispatch(object ...$event): void;
}
