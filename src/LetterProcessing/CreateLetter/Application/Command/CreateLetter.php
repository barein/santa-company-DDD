<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateLetter\Application\Command;

use App\LetterProcessing\Shared\Domain\Letter\Letter;
use App\Shared\Infrastructure\Symfony\ValidationConstraint\IsoCountryCodeConstraint;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ulid;

readonly class CreateLetter
{
    public function __construct(
        #[Ulid]
        public string $letterId,

        #[Ulid]
        public string $childId,

        #[DateTime(format: Letter::RECEIVING_DATE_FORMAT)]
        public string $receivingDate,

        public int $senderStreetNumber,

        #[NotBlank]
        public string $senderStreet,

        #[NotBlank]
        public string $senderCity,

        public int $senderZipCode,

        #[IsoCountryCodeConstraint]
        public string $senderIsoCountryCode,
    ) {
    }
}
