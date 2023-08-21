<?php

declare(strict_types=1);

namespace App\LetterProcessing\GetChildren\Application\ReadModel\V1;

use App\LetterProcessing\Shared\Application\ReadModel\Child\V1\ChildReadModel;

readonly class ChildrenReadModel
{
    /**
     * @param ChildReadModel[] $children
     */
    public function __construct(
        public array $children,
    ) {
    }
}
