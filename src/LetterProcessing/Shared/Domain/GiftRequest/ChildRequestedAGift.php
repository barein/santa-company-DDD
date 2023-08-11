<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain\GiftRequest;

use App\LetterProcessing\Shared\Domain\AbstractLetterProcessingEvent;
use App\Shared\Domain\Event\DomainEventInterface;
use Symfony\Component\Validator\Constraints\Ulid;

class ChildRequestedAGift extends AbstractLetterProcessingEvent implements DomainEventInterface
{
    public function __construct(
        #[Ulid]
        public readonly string $childId,
        #[Ulid]
        public readonly string $letterId,
        #[Ulid]
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
