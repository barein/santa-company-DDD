<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Infrastructure\Symfony\ValidationConstraint\IsoCountryCodeConstraint;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

class NewChildSentLetter extends AbstractLetterProcessingEvent implements DomainEventInterface
{
    public function __construct(
        #[UlidConstraint]
        public readonly string $childId,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly int $streetNumber,
        public readonly string $streetName,
        public readonly string $city,
        public readonly int $zipCode,
        #[IsoCountryCodeConstraint]
        public readonly string $isoCountryCode,
    ) {
        parent::__construct();
    }

    public static function getName(): string
    {
        return 'new_child_sent_letter';
    }

    public static function getVersion(): int
    {
        return 1;
    }
}
