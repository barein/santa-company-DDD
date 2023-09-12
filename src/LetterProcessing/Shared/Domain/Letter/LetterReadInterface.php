<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain\Letter;

use App\Shared\Domain\Address;
use Symfony\Component\Uid\Ulid;

interface LetterReadInterface
{
    public function getId(): Ulid;

    public function getSenderAddress(): Address;

    public function getReceivingDate(): \DateTimeImmutable;
}
