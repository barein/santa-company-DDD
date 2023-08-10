<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateGiftRequest\Application\Command;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ulid;

readonly class CreateGiftRequest
{
    public function __construct(
        #[Ulid]
        public string $giftRequestId,

        #[Ulid]
        public string $childId,

        #[Ulid]
        public string $letterId,

        #[NotBlank]
        public string $giftName,
    ) {
    }
}
