<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\LetterProcessing\Shared\Infrastructure\DoctrineChildRepository;
use App\Shared\Domain\Address;
use App\Shared\Domain\Event\AggregateRoot;
use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Infrastructure\Doctrine\DBAL\Type\UlidType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: DoctrineChildRepository::class)]
#[ORM\Table(name: 'child_of_letter_processing')]
#[ORM\HasLifecycleCallbacks]
class Child extends AggregateRoot
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

    /**
     * @var Collection<Letter>
     */
    #[ORM\OneToMany(mappedBy: 'child', targetEntity: Letter::class, cascade: ['all'], orphanRemoval: true)]
    private Collection $letters;

    public function __construct(Ulid $id, string $firstName, string $lastName, Address $address)
    {
        $this->id = $id;
        $this->letters = new ArrayCollection();
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->address = $address;

        $this->raiseEvent(new NewChildSentLetter(
            (string) $this->id,
            $this->firstName,
            $this->lastName,
            $address->getNumber(),
            $address->getStreet(),
            $address->getCity(),
            $address->getZipCode(),
            $address->getIsoCountryCode()->getValue(),
        ));
    }

    public function getId(): Ulid
    {
        return $this->id;
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

    /**
     * @throws LetterAlreadySentThisYearException
     */
    public function sentLetter(Ulid $letterId, \DateTimeImmutable $receivedOn, Address $from): void
    {
        $newLetter = new Letter($letterId, $this, $from, $receivedOn);

        if ($this->letters->contains($newLetter)) {
            return;
        }

        $lettersReceivedSameYear = $this->letters->filter(
            fn (Letter $letter) => $letter->getReceivingDate()->format('Y') === $newLetter->getReceivingDate()->format('Y')
        );

        if ($lettersReceivedSameYear->isEmpty() === false) {
            throw new LetterAlreadySentThisYearException(sprintf(
                'Child %s has already sent a letter this same year',
                $this->id,
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
     * @throws GiftAlreadyRequestedInLetterException
     * @throws NotFoundException
     */
    public function requestsAGift(Ulid $giftRequestId, Ulid $letterId, string $giftName): void
    {
        $letter = $this->getLetterById($letterId);
        $letter->mentionGiftRequest($giftRequestId, $giftName);

        $this->raiseEvent(new ChildRequestedAGift((string) $this->id, (string) $letterId, (string) $giftRequestId));
    }

    /**
     * @throws NotFoundException
     */
    private function getLetterById(Ulid $letterId): Letter
    {
        /** @var ?Letter $letter */
        $letter = $this->letters->findFirst(fn (int $index, Letter $letter) => $letter->getId()->equals($letterId));

        if ($letter === null) {
            throw new NotFoundException(sprintf(
                'Letter %s could not be found',
                $letterId,
            ));
        }

        return $letter;
    }

    #[ORM\PreRemove]
    public function onRemove(): void
    {
        $this->raiseEvent(new ChildWasRemoved((string) $this->id));
    }

    /**
     * @throws NotFoundException
     */
    public function giftRequestGranted(Ulid $giftRequestId, Ulid $letterId): void
    {
        $giftRequest = $this->getLetterById($letterId)->getGiftRequestById($giftRequestId);
        $giftRequest->grant();

        $this->raiseEvent(new GiftRequestWasGranted(
            childId: (string) $this->getId(),
            letterId: (string) $letterId,
            giftRequestId: (string) $giftRequest->getId(),
            giftName: $giftRequest->getGiftName(),
        ));
    }

    /**
     * @throws NotFoundException
     */
    public function giftRequestDeclined(Ulid $giftRequestId, Ulid $letterId): void
    {
        $this->getLetterById($letterId)
            ->getGiftRequestById($giftRequestId)
            ->decline();
    }
}
