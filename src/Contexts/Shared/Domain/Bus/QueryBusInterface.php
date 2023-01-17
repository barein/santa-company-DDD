<?php

namespace App\Contexts\Shared\Domain\Bus;

interface QueryBusInterface
{
    public function query(object $query): mixed;
}