<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

use Symfony\Component\Uid\Ulid;

interface DomainEventInterface
{
    public const OCCURRED_ON_FORMAT = 'Y-m-d H:i:s';

    public function getId(): string;

    /**
     * For deserialization of event
     */
    public function setId(Ulid $id): void;

    public static function getName(): string;

    public static function getContext(): string;

    public static function getVersion(): int;

    public function getOccurredOn(): string;

    /**
     * For deserialization of event
     */
    public function setOccurredOn(\DateTimeImmutable $occurredOn): void;
}
