<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

use App\Shared\Infrastructure\Event\DoctrineEventStore;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: DoctrineEventStore::class)]
class StoredEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $ulid;

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

    public function __construct(
        string $name,
        string $context,
        \DateTimeImmutable $occurredOn,
        int $version,
        string $body
    ) {
        $this->ulid = new Ulid();
        $this->name = $name;
        $this->context = $context;
        $this->occurredOn = $occurredOn;
        $this->version = $version;
        $this->body = $body;
    }

    public function getUlid(): Ulid
    {
        return $this->ulid;
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
}
