<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Exception\LogicException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class Letter
{
    public const RECEIVING_DATE_FORMAT = 'Y-m-d';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $ulid;

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

    public function __construct(Child $child, Address $senderAddress, \DateTimeImmutable $receivingDate)
    {
        $this->ulid = new Ulid();
        $this->child = $child;
        $this->senderAddress = $senderAddress;
        $this->giftRequests = new ArrayCollection();
        $this->receivingDate = $receivingDate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUlid(): Ulid
    {
        return $this->ulid;
    }

    public function getChild(): Child
    {
        return $this->child;
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
     * @return Collection<GiftRequest>
     */
    public function getGiftRequests(): Collection
    {
        return $this->giftRequests;
    }

    public function mentionGiftRequest(string $giftName): ?GiftRequest
    {
        $newGiftRequest = new GiftRequest($this, $giftName);

        if ($this->giftRequests->contains($newGiftRequest)) {
            return null;
        }

        if ($this->giftRequests->count() >= 4) {
            throw new MaximumNumberOfGiftRequestPerLetterReachedException(sprintf(
                'Letter %s already contains maximum number of GiftRequest',
                $this->ulid,
            ));
        }

        $giftRequestsWithSameName = $this->giftRequests->filter(
            fn (GiftRequest $giftRequest) => $giftRequest->getGiftName() === $newGiftRequest->getGiftName()
        );

        if (!$giftRequestsWithSameName->isEmpty()) {
            throw new LogicException('This letter already request a Gift with this exact name');
        }

        $this->giftRequests->add($newGiftRequest);

        return $newGiftRequest;
    }
}
