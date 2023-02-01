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
        public readonly string $childUlid,
        #[UlidConstraint]
        public readonly string $letterUlid,
        #[UlidConstraint]
        public readonly string $giftRequestUlid,
    ) {
        parent::__construct();
    }

    public function getChildUlid(): Ulid
    {
        return new Ulid($this->childUlid);
    }

    public function getLetterUlid(): Ulid
    {
        return new Ulid($this->letterUlid);
    }

    public function getGiftRequestUlid(): Ulid
    {
        return new Ulid($this->giftRequestUlid);
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
