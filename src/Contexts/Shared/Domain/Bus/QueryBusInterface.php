<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Domain\Bus;

interface QueryBusInterface
{
    public function query(object $query): mixed;
}
