<?php

declare(strict_types=1);

namespace App\LetterProcessing\GetChildren\Application\Query;

use App\Shared\Application\ApiVersion;

readonly class GetChildren
{
    public function __construct(
        public ApiVersion $version,
    ) {
    }
}
