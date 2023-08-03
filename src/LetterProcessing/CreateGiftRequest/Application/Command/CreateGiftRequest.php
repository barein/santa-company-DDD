<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateGiftRequest\Application\Command;

use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

readonly class CreateGiftRequest
{
    public function __construct(
        #[UlidConstraint]
        public string $childId,
        #[UlidConstraint]
        public string $letterId,
        #[NotBlank]
        public string $giftName,
    ) {
    }

    public function getChildId(): Ulid
    {
        return new Ulid($this->childId);
    }

    public function getLetterId(): Ulid
    {
        return new Ulid($this->letterId);
    }

    public function getGiftName(): string
    {
        return $this->giftName;
    }
}
