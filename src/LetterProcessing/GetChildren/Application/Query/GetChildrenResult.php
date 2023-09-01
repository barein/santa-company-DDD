<?php

declare(strict_types=1);

namespace App\LetterProcessing\GetChildren\Application\Query;

use App\LetterProcessing\Shared\Domain\Child\ChildReadInterface;

readonly class GetChildrenResult
{
    /**
     * @param array<ChildReadInterface> $children
     */
    public function __construct(
        public array $children,
    ) {
    }
}
