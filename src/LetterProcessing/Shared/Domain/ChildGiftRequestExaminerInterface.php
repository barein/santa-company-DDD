<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use Symfony\Component\Uid\Ulid;

interface ChildGiftRequestExaminerInterface
{
    public function examine(Child $child, Ulid $letterId, Ulid $giftRequestId): void;
}
