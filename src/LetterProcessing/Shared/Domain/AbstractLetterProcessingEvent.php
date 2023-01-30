<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEvent;
use Symfony\Component\Uid\Ulid;

class AbstractLetterProcessingEvent
{
    private \DateTimeImmutable $occurredOn;

    private Ulid $ulid;

    public function __construct()
    {
        $this->occurredOn = new \DateTimeImmutable();
        $this->ulid = new Ulid();
    }

    public function getContext(): string
    {
        return 'letter_processing';
    }

    public function getOccurredOn(): string
    {
        return $this->occurredOn->format(DomainEvent::OCCURRED_ON_FORMAT);
    }

    public function getUlid(): string
    {
        return (string) $this->ulid;
    }
}
