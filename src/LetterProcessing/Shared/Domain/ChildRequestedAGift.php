<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEvent;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

class ChildRequestedAGift extends AbstractLetterProcessingEvent implements DomainEvent
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

    public function getChildId(): Ulid
    {
        return new Ulid($this->childId);
    }

    public function getLetterId(): Ulid
    {
        return new Ulid($this->letterId);
    }

    public function getGiftRequestId(): Ulid
    {
        return new Ulid($this->giftRequestId);
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
