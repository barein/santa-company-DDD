<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\Application\ReadModel;

use App\ChildWatching\Shared\Domain\Action;
use App\ChildWatching\Shared\Domain\Child;

readonly class GetChildReadModelDto
{
    /**
     * @param array<Action> $currentYearActions
     */
    public function __construct(
        public Child $child,
        public array $currentYearActions,
    ) {
    }
}
