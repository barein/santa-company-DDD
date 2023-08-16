<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Infrastructure\Doctrine\DBAL\Type\UlidType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: DoctrineReceivedEventStore::class)]
class ReceivedEvent
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    #[ORM\Column(length: 100)]
    private string $emitterContext;

    /**
     * @var string[] $contexts
     */
    #[ORM\Column(type: Types::JSON)]
    private array $contexts;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $occurredOn;

    #[ORM\Column]
    private int $version;

    #[ORM\Column(type: 'text')]
    private string $body;

    #[ORM\Column(type: 'boolean')]
    private bool $handledSuccessfully = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $exceptionsLog = null;

    /**
     * @param array<string> $contexts
     */
    public function __construct(
        Ulid $id,
        string $name,
        string $emitterContext,
        array $contexts,
        \DateTimeImmutable $occurredOn,
        int $version,
        string $body
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->emitterContext = $emitterContext;
        $this->contexts = $contexts;
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

    public function getEmitterContext(): string
    {
        return $this->emitterContext;
    }

    /**
     * @return string[]
     */
    public function getContexts(): array
    {
        return $this->contexts;
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

    public function markAsHandledSuccessfully(): void
    {
        $this->handledSuccessfully = true;
    }

    public function hasBeenHandledSuccessfully(): bool
    {
        return $this->handledSuccessfully;
    }

    public function logException(\Throwable $exception): void
    {
        $this->exceptionsLog .=
            $this->exceptionsLog === null ?
            sprintf('%s: %s', get_debug_type($exception), $exception->getMessage()) :
            sprintf(' | %s: %s', get_debug_type($exception), $exception->getMessage());
    }
}
