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
        public string $childUlid,
        #[UlidConstraint]
        public string $letterUlid,
        #[NotBlank]
        public string $giftName,
    ) {
    }

    public function getChildUlid(): Ulid
    {
        return new Ulid($this->childUlid);
    }

    public function getLetterUlid(): Ulid
    {
        return new Ulid($this->letterUlid);
    }

    public function getGiftName(): string
    {
        return $this->giftName;
    }
}
