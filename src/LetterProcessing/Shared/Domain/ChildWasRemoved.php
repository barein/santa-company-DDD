<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEvent;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

class ChildWasRemoved extends AbstractLetterProcessingEvent implements DomainEvent
{
    public function __construct(
        #[UlidConstraint]
        public readonly string $childId,
    ) {
        parent::__construct();
    }

    public function getChildId(): Ulid
    {
        return new Ulid($this->childId);
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
