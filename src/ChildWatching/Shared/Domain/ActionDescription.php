<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

use App\Shared\Domain\Exception\InvalidArgumentException;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class ActionDescription
{
    public const MAX_LENGTH = 255;

    #[Column(name: 'description', length: self::MAX_LENGTH)]
    private string $value;

    private function __construct(string $description)
    {
        if ($this->isDescriptionLengthValid($description) === false) {
            throw new InvalidArgumentException('Action description length should be between 1 and 255 characters');
        }

        $this->value = $description;
    }

    public static function fromString(string $description): self
    {
        return new self($description);
    }

    private function isDescriptionLengthValid(string $description): bool
    {
        $description = trim($description);

        return \strlen($description) >= 1 && \strlen($description) <= self::MAX_LENGTH;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
