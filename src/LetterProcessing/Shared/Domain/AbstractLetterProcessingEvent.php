<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEvent;
use Symfony\Component\Uid\Ulid;

class AbstractLetterProcessingEvent
{
    private Ulid $ulid;

    private \DateTimeImmutable $occurredOn;

    public function __construct()
    {
        $this->ulid = new Ulid();
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getUlid(): string
    {
        return (string) $this->ulid;
    }

    public static function getContext(): string
    {
        return 'letter_processing';
    }

    public function getOccurredOn(): string
    {
        return $this->occurredOn->format(DomainEvent::OCCURRED_ON_FORMAT);
    }
}
