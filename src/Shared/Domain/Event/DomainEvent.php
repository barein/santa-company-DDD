<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

interface DomainEvent
{
    public function getName(): string;

    public function getOccurredOn(): \DateTimeImmutable;

    public function getVersion(): int;
}
