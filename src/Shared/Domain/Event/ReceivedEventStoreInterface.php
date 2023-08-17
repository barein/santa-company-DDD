<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Infrastructure\Event\ReceivedEvent;
use Symfony\Component\Uid\Ulid;

interface ReceivedEventStoreInterface
{
    /**
     * @param array<string> $receivingContexts
     */
    public function append(DomainEventInterface $domainEvent, array $receivingContexts): void;

    /**
     * @throws NotFoundException
     */
    public function get(Ulid $id): ReceivedEvent;

    public function store(): void;
}
