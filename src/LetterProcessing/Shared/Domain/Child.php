<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\LetterProcessing\Shared\Infrastructure\DoctrineChildRepository;
use App\Shared\Domain\Aggregate;
use App\Shared\Domain\Exception\LogicException;
use App\Shared\Domain\Exception\NotFoundException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: DoctrineChildRepository::class)]
#[ORM\Table(name: 'child_of_letter_processing')]
class Child extends Aggregate
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

        $this->storeEvent(new ChildWasCreated((string) $this->ulid));
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

        if (!$this->address->equal($newLetter->getSenderAddress())) {
            $this->updateAdress($newLetter->getSenderAddress());
        }
    }

    private function updateAdress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @throws MaximumNumberOfGiftRequestPerLetterReachedException
     * @throws NotFoundException
     * @throws LogicException
     */
    public function requestedAGift(Ulid $letterUlid, string $giftName): void
    {
        $letter = $this->getLetterByUlid($letterUlid);
        $letter->mentionGiftRequest($giftName);
    }

    /**
     * @throws NotFoundException
     */
    private function getLetterByUlid(Ulid $letterUlid): Letter
    {
        /** @var ?Letter $letter */
        $letter = $this->letters->findFirst(fn (Letter $letter) => $letter->getUlid()->equals($letterUlid));

        if ($letter === null) {
            throw new NotFoundException(sprintf(
                'Letter %s could not be found',
                $letterUlid,
            ));
        }

        return $letter;
    }
}
