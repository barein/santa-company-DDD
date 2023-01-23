<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

enum ActionType: string
{
    case GOOD = 'GOOD';
    case BAD = 'BAD';
}
