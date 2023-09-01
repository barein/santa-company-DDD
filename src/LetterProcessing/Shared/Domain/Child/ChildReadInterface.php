<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain\Child;

use App\LetterProcessing\Shared\Domain\Letter\LetterReadInterface;
use App\Shared\Domain\Address;
use Symfony\Component\Uid\Ulid;

interface ChildReadInterface
{
    public function getId(): Ulid;

    public function getFirstName(): string;

    public function getLastName(): string;

    public function getAddress(): Address;

    /**
     * @return array<LetterReadInterface>
     */
    public function getLetters(): array;
}
