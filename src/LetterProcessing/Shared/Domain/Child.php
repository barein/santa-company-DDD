<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\LetterProcessing\Shared\Infrastructure\DoctrineChildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: DoctrineChildRepository::class)]
#[ORM\Table(name: 'child_of_letter_processing')]
class Child
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $ulid;

    #[ORM\Column(length: 255)]
    private string $firstName;

    #[ORM\Column(length: 255)]
    private string $lastName;

    #[ORM\Embedded(class: Address::class, columnPrefix: 'address_')]
    private Address $address;

    /**
     * @var Collection<Letter>
     */
    #[ORM\OneToMany(mappedBy: 'child', targetEntity: Letter::class, cascade: ['all'], orphanRemoval: true)]
    private Collection $letters;

    public function __construct(string $firstName, string $lastName, Address $address)
    {
        $this->ulid = new Ulid();
        $this->letters = new ArrayCollection();
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

    /**
     * @return Collection<Letter>
     */
    public function getLetters(): Collection
    {
        return $this->letters;
    }

    public function sentLetter(\DateTimeImmutable $receivedOn, Address $from): void
    {
        $newLetter = new Letter($this, $from, $receivedOn);

        if ($this->letters->contains($newLetter)) {
            return;
        }

        $lettersReceivedSameYear = $this->letters->filter(
            fn (Letter $letter) => $letter->getReceivingDate()->format('Y') === $newLetter->getReceivingDate()->format('Y')
        );

        if ($lettersReceivedSameYear->isEmpty() === false) {
            throw new LetterAlreadySentThisYearException(sprintf(
                'Child %s has already sent a letter this same year',
                $this->ulid,
            ));
        }

        $this->letters->add($newLetter);
    }
}
