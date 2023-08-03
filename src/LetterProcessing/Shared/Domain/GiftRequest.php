<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Infrastructure\Doctrine\DBAL\Type\UlidType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class GiftRequest
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    #[ORM\ManyToOne(targetEntity: Letter::class, inversedBy: 'giftRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private Letter $letter;

    #[ORM\Column(length: 255)]
    private string $giftName;

    #[ORM\Column(length: 255, enumType: GiftRequestStatus::class)]
    private GiftRequestStatus $status;

    public function __construct(Letter $letter, string $giftName)
    {
        $this->id = new Ulid();
        $this->letter = $letter;
        $this->giftName = $giftName;
        $this->status = GiftRequestStatus::PENDING;
    }

    public function getId(): Ulid
    {
        return $this->id;
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

    public function grant(): void
    {
        $this->status = GiftRequestStatus::GRANTED;
    }

    public function decline(): void
    {
        $this->status = GiftRequestStatus::DECLINED;
    }
}
