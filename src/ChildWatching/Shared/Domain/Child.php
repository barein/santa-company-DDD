<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

use App\ChildWatching\Shared\Infrastructure\DoctrineChildRepository;
use App\Shared\Domain\Address;
use App\Shared\Infrastructure\Doctrine\DBAL\Type\UlidType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: DoctrineChildRepository::class)]
#[ORM\Table(name: 'child_of_child_watching')]
class Child implements ChildReadInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    #[ORM\Column(length: 255)]
    private string $firstName;

    #[ORM\Column(length: 255)]
    private string $lastName;

    #[ORM\Embedded(class: Address::class, columnPrefix: 'address_')]
    private Address $address;

    public function __construct(
        Ulid $id,
        string $firstName,
        string $lastName,
        Address $address,
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->address = $address;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function madeAction(
        Ulid $actionId,
        \DateTimeImmutable $dateTime,
        ActionDescription $description,
        ActionType $type
    ): Action {
        return new Action($actionId, $this->getId(), $dateTime, $description, $type);
    }

    public function movedTo(Address $address): void
    {
        $this->address = $address;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }
}
