<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEventInterface;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

class ChildRequestedAGift extends AbstractLetterProcessingEvent implements DomainEventInterface
{
    public function __construct(
        #[UlidConstraint]
        public readonly string $childId,
        #[UlidConstraint]
        public readonly string $letterId,
        #[UlidConstraint]
        public readonly string $giftRequestId,
    ) {
        parent::__construct();
    }

    public static function getName(): string
    {
        return 'child_requested_gift';
    }

    public static function getVersion(): int
    {
        return 1;
    }
}
