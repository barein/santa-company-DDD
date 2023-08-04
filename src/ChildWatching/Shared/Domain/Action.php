<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

use App\ChildWatching\Shared\Infrastructure\DoctrineActionRepository;
use App\Shared\Infrastructure\Doctrine\DBAL\Type\UlidType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: DoctrineActionRepository::class)]
class Action
{
    public const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $childId;

    #[ORM\Column]
    private \DateTimeImmutable $dateTime;

    #[ORM\Embedded(class: ActionDescription::class, columnPrefix: false)]
    private ActionDescription $description;

    #[ORM\Column(length: 255, enumType: ActionType::class)]
    private ActionType $type;

    public function __construct(
        Ulid $id,
        Ulid $childId,
        \DateTimeImmutable $dateTime,
        ActionDescription $description,
        ActionType $type
    ) {
        $this->id = $id;
        $this->childId = $childId;
        $this->dateTime = $dateTime;
        $this->description = $description;
        $this->type = $type;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getChildId(): Ulid
    {
        return $this->childId;
    }

    public function getDateTime(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function getDescription(): ActionDescription
    {
        return $this->description;
    }

    public function getType(): ActionType
    {
        return $this->type;
    }
}
