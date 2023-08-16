<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Event\DomainEventInterface;
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

    public function setId(Ulid $id): void
    {
        $this->id = $id;
    }

    public static function getContext(): string
    {
        return 'letter_processing';
    }

    public function getOccurredOn(): string
    {
        return $this->occurredOn->format(DomainEventInterface::OCCURRED_ON_FORMAT);
    }

    public function setOccurredOn(\DateTimeImmutable $occurredOn): void
    {
        $this->occurredOn = $occurredOn;
    }
}
