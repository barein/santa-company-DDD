<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEvent;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

class ChildWasCreated implements DomainEvent
{
    private \DateTimeImmutable $occurredOn;

    public function __construct(
        #[UlidConstraint]
        public string $childUlid,
    ) {
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getName(): string
    {
        return 'child_was_created';
    }

    // A scalar value should be public for serialization
    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getVersion(): int
    {
        return 1;
    }

    public function getChildUlid(): Ulid
    {
        return new Ulid($this->childUlid);
    }
}
