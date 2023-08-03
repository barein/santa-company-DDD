<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEvent;
use Symfony\Component\Uid\Ulid;

class AbstractLetterProcessingEvent
{
    private Ulid $id;

    private \DateTimeImmutable $occurredOn;

    public function __construct()
    {
        $this->id = new Ulid();
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return (string) $this->id;
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
