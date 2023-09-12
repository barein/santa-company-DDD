<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

interface ActionReadInterface
{
    public function getType(): ActionType;
}
