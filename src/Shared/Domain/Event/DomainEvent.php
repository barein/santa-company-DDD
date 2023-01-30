<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

interface DomainEvent
{
    public const OCCURRED_ON_FORMAT = 'Y-m-d H:i:s';

    public function getName(): string;

    public function getOccurredOn(): string;

    public function getVersion(): int;
}
