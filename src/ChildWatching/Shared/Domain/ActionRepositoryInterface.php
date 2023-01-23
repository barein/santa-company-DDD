<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

interface ActionRepositoryInterface
{
    public function add(Action $action): void;
}
