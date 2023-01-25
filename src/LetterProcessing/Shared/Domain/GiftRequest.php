<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\LetterProcessing\Shared\Infrastructure\DoctrineGiftRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: DoctrineGiftRequestRepository::class)]
class GiftRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $ulid;

    #[ORM\ManyToOne(targetEntity: Letter::class, inversedBy: 'giftRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private Letter $letter;

    #[ORM\Column(length: 255)]
    private string $giftName;

    #[ORM\Column(length: 255, enumType: GiftRequestStatus::class)]
    private GiftRequestStatus $status;

    public function __construct(Letter $letter, string $giftName)
    {
        $this->ulid = new Ulid();
        $this->letter = $letter;
        $this->giftName = $giftName;
        $this->status = GiftRequestStatus::PENDING;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUlid(): Ulid
    {
        return $this->ulid;
    }

    public function getLetter(): Letter
    {
        return $this->letter;
    }

    public function getGiftName(): string
    {
        return $this->giftName;
    }

    public function getStatus(): GiftRequestStatus
    {
        return $this->status;
    }
}
