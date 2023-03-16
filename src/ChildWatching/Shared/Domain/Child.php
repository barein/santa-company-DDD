<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

use App\ChildWatching\Shared\Infrastructure\DoctrineChildRepository;
use App\Shared\Domain\Address;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: DoctrineChildRepository::class)]
#[ORM\Table(name: 'child_of_child_watching')]
class Child
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $ulid;

    #[ORM\Column(length: 255)]
    private string $firstName;

    #[ORM\Column(length: 255)]
    private string $lastName;

    #[ORM\Embedded(class: Address::class, columnPrefix: 'address_')]
    private Address $address;

    public function __construct(
        Ulid $ulid,
        string $firstName,
        string $lastName,
        Address $address,
    ) {
        $this->ulid = $ulid;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->address = $address;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUlid(): Ulid
    {
        return $this->ulid;
    }

    public function madeAction(\DateTimeImmutable $dateTime, ActionDescription $description, ActionType $type): Action
    {
        return new Action($this->getUlid(), $dateTime, $description, $type);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }
}
