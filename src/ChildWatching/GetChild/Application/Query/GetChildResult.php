<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\Application\Query;

use App\ChildWatching\Shared\Domain\ActionReadInterface;
use App\ChildWatching\Shared\Domain\ChildReadInterface;

readonly class GetChildResult
{
    /**
     * @param array<ActionReadInterface> $currentYearActions
     */
    public function __construct(
        public ChildReadInterface $child,
        public array $currentYearActions,
    ) {
    }
}
