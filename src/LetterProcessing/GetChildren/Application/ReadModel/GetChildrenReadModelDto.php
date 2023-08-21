<?php

declare(strict_types=1);

namespace App\LetterProcessing\GetChildren\Application\ReadModel;

use App\LetterProcessing\Shared\Domain\Child\Child;

readonly class GetChildrenReadModelDto
{
    /**
     * @param array<Child> $children
     */
    public function __construct(
        public array $children,
    ) {
    }
}
