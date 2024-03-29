<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain\Child;

use App\LetterProcessing\Shared\Domain\AbstractLetterProcessingEvent;
use App\Shared\Domain\Event\DomainEventInterface;
use Symfony\Component\Validator\Constraints\Ulid;

class ChildWasRemoved extends AbstractLetterProcessingEvent implements DomainEventInterface
{
    public function __construct(
        #[Ulid]
        public readonly string $childId,
    ) {
        parent::__construct();
    }

    public static function getName(): string
    {
        return 'child_was_removed';
    }

    public static function getVersion(): int
    {
        return 1;
    }
}
