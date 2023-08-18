<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain\Child;

use App\LetterProcessing\Shared\Domain\AbstractLetterProcessingEvent;
use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Infrastructure\Symfony\ValidationConstraint\IsoCountryCodeConstraint;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ulid;

class ChildMoved extends AbstractLetterProcessingEvent implements DomainEventInterface
{
    public function __construct(
        #[Ulid]
        public readonly string $childId,
        #[GreaterThan(0)]
        public readonly int $streetNumber,
        #[NotBlank]
        public readonly string $streetName,
        #[NotBlank]
        public readonly string $city,
        public readonly int $zipCode,
        #[IsoCountryCodeConstraint]
        public readonly string $isoCountryCode,
    ) {
        parent::__construct();
    }

    public static function getName(): string
    {
        return 'child_sent_letter_from_new_address';
    }

    public static function getVersion(): int
    {
        return 1;
    }
}
