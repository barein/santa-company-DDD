<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

use App\ChildWatching\Shared\Infrastructure\DoctrineActionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: DoctrineActionRepository::class)]
#[ORM\Index(columns: ['child_ulid'], name: 'child_ulid_index')]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $ulid;

    #[ORM\Column(type: 'ulid')]
    private Ulid $childUlid;

    #[ORM\Column]
    private \DateTimeImmutable $dateTime;

    #[ORM\Column(length: 255)]
    private string $description;

    #[ORM\Column(length: 255, enumType: ActionType::class)]
    private ActionType $type;

    public function __construct(Ulid $childUlid, \DateTimeImmutable $dateTime, string $description, ActionType $type)
    {
        $this->ulid = new Ulid();
        $this->childUlid = $childUlid;
        $this->dateTime = $dateTime;
        $this->description = $description;
        $this->type = $type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUlid(): Ulid
    {
        return $this->ulid;
    }

    public function getChildUlid(): Ulid
    {
        return $this->childUlid;
    }

    public function getDateTime(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getType(): ActionType
    {
        return $this->type;
    }
}
