<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Infrastructure\ValidationConstraint\IsoCountryCodeConstraint;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

class ChildWasCreated extends AbstractLetterProcessingEvent implements DomainEvent
{
    public function __construct(
        #[UlidConstraint]
        public readonly string $childUlid,
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

    public function getChildUlid(): Ulid
    {
        return new Ulid($this->childUlid);
    }

    public static function getName(): string
    {
        return 'child_was_created';
    }

    public static function getVersion(): int
    {
        return 1;
    }
}
