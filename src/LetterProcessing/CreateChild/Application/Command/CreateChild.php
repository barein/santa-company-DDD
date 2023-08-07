<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateChild\Application\Command;

use App\Shared\Infrastructure\ValidationConstraint\IsoCountryCodeConstraint;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ulid;

readonly class CreateChild
{
    public function __construct(
        #[Ulid]
        public string $childId,

        #[NotBlank]
        public string $firstName,

        #[NotBlank]
        public string $lastName,

        #[GreaterThan(0)]
        public int $streetNumber,

        #[NotBlank]
        public string $street,

        #[NotBlank]
        public string $city,

        public int $zipCode,

        #[IsoCountryCodeConstraint]
        public string $isoCountryCode,
    ) {
    }
}
