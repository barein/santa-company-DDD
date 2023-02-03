<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEvent;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

class GiftRequestWasGranted extends AbstractLetterProcessingEvent implements DomainEvent
{
    public function __construct(
        #[UlidConstraint]
        public readonly string $childUlid,
        #[UlidConstraint]
        public readonly string $giftRequestUlid,
        #[NotBlank]
        public readonly string $giftName,
    ) {
        parent::__construct();
    }

    public function getChildUlid(): Ulid
    {
        return new Ulid($this->childUlid);
    }

    public function getGiftRequestUlid(): Ulid
    {
        return new Ulid($this->giftRequestUlid);
    }

    public function getGiftName(): string
    {
        return $this->giftName;
    }

    public static function getName(): string
    {
        return 'gift_request_was_granted';
    }

    public static function getVersion(): int
    {
        return 1;
    }
}
