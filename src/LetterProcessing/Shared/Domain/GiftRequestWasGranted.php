<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEventInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ulid;

class GiftRequestWasGranted extends AbstractLetterProcessingEvent implements DomainEventInterface
{
    public function __construct(
        #[Ulid]
        public readonly string $childId,
        #[Ulid]
        public readonly string $letterId,
        #[Ulid]
        public readonly string $giftRequestId,
        #[NotBlank]
        public readonly string $giftName,
    ) {
        parent::__construct();
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
