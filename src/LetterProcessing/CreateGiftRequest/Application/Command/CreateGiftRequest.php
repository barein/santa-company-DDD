<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateGiftRequest\Application\Command;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ulid;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

readonly class CreateGiftRequest
{
    public function __construct(
        #[Ulid]
        public string $giftRequestId,

        #[UlidConstraint]
        public string $childId,

        #[UlidConstraint]
        public string $letterId,

        #[NotBlank]
        public string $giftName,
    ) {
    }
}
