<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\Application\Query;

use Symfony\Component\Uid\Ulid;

readonly class GetChild
{
    public function __construct(
        public Ulid $childId,
    ) {
    }
}
