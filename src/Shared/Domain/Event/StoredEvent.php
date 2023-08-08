<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

use App\Shared\Infrastructure\Doctrine\DBAL\Type\UlidType;
use App\Shared\Infrastructure\Event\DoctrineEventStore;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: DoctrineEventStore::class)]
class StoredEvent
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    #[ORM\Column(length: 100)]
    private string $context;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $occurredOn;

    #[ORM\Column]
    private int $version;

    #[ORM\Column(type: 'text')]
    private string $body;

    #[ORM\Column(type: 'boolean')]
    private bool $dispatched = false;

    public function __construct(
        Ulid $id,
        string $name,
        string $context,
        \DateTimeImmutable $occurredOn,
        int $version,
        string $body
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->context = $context;
        $this->occurredOn = $occurredOn;
        $this->version = $version;
        $this->body = $body;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function markAsDispatched(): void
    {
        $this->dispatched = true;
    }

    public function hasBeenDispatched(): bool
    {
        return $this->dispatched;
    }
}
