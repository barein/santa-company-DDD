<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

interface DomainEventInterface
{
    public const OCCURRED_ON_FORMAT = 'Y-m-d H:i:s';

    public function getId(): string;

    public static function getName(): string;

    public static function getContext(): string;

    public static function getVersion(): int;

    public function getOccurredOn(): string;
}
