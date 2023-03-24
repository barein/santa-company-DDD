<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\Application\Query;

use App\Shared\Application\ApiVersion;
use Symfony\Component\Uid\Ulid;

readonly class GetChild
{
    public function __construct(
        public Ulid $childUlid,
        public ApiVersion $apiVersion,
    ) {
    }
}
