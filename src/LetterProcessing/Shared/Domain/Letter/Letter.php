<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain\Letter;

use App\LetterProcessing\Shared\Domain\Child\Child;
use App\LetterProcessing\Shared\Domain\GiftRequest\GiftAlreadyRequestedInLetterException;
use App\LetterProcessing\Shared\Domain\GiftRequest\GiftRequest;
use App\LetterProcessing\Shared\Domain\GiftRequest\MaximumNumberOfGiftRequestPerLetterReachedException;
use App\Shared\Domain\Address;
use App\Shared\Domain\Exception\NotFoundException;
use App\Shared\Infrastructure\Doctrine\DBAL\Type\UlidType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class Letter implements LetterReadInterface
{
    public const RECEIVING_DATE_FORMAT = 'Y-m-d';
    public const MAX_NUMBER_OF_GIFT_REQUEST_PER_LETTER = 4;

    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    #[ORM\ManyToOne(targetEntity: Child::class, inversedBy: 'letters')]
    #[ORM\JoinColumn(nullable: false)]
    private Child $child;

    #[ORM\Embedded(class: Address::class, columnPrefix: 'sender_address_')]
    private Address $senderAddress;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $receivingDate;

    /**
     * @var Collection<GiftRequest>
     */
    #[ORM\OneToMany(mappedBy: 'letter', targetEntity: GiftRequest::class, cascade: ['all'], orphanRemoval: true)]
    private Collection $giftRequests;

    public function __construct(Ulid $id, Child $child, Address $senderAddress, \DateTimeImmutable $receivingDate)
    {
        $this->id = $id;
        $this->child = $child;
        $this->senderAddress = $senderAddress;
        $this->giftRequests = new ArrayCollection();
        $this->receivingDate = $receivingDate;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getSenderAddress(): Address
    {
        return $this->senderAddress;
    }

    public function getReceivingDate(): \DateTimeImmutable
    {
        return $this->receivingDate;
    }

    /**
     * @throws GiftAlreadyRequestedInLetterException
     * @throws MaximumNumberOfGiftRequestPerLetterReachedException
     */
    public function mentionGiftRequest(Ulid $giftRequestId, string $giftName): void
    {
        if ($this->giftRequests->count() >= self::MAX_NUMBER_OF_GIFT_REQUEST_PER_LETTER) {
            throw new MaximumNumberOfGiftRequestPerLetterReachedException(sprintf(
                'Letter %s already contains the maximum number of GiftRequest (%d)',
                $this->id,
                self::MAX_NUMBER_OF_GIFT_REQUEST_PER_LETTER,
            ));
        }

        $newGiftRequest = new GiftRequest($giftRequestId, $this, $giftName);

        $giftRequestsWithSameName = $this->giftRequests->filter(
            fn (GiftRequest $giftRequest) => $giftRequest->getGiftName() === $newGiftRequest->getGiftName()
        );

        if ($giftRequestsWithSameName->isEmpty() === false) {
            throw new GiftAlreadyRequestedInLetterException(sprintf(
                'Gift %s was already requested in letter %s',
                $giftName,
                $this->id,
            ));
        }

        $this->giftRequests->add($newGiftRequest);
    }

    /**
     * @throws NotFoundException
     */
    public function getGiftRequestById(Ulid $giftRequestId): GiftRequest
    {
        /** @var ?GiftRequest $giftRequest */
        $giftRequest = $this->giftRequests->findFirst(fn (int $index, GiftRequest $giftRequest) => $giftRequest->getId()->equals($giftRequestId));

        if ($giftRequest === null) {
            throw new NotFoundException(sprintf(
                'GiftRequest %s could not be found',
                $giftRequestId,
            ));
        }

        return $giftRequest;
    }
}
