<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Exception\InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class IsoCountryCode
{
    #[ORM\Column(name: 'iso_country_code', length: 3)]
    private string $value;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(string $isoCountryCode)
    {
        $this->validateIsoCountryCode($isoCountryCode);
        $this->value = $isoCountryCode;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromCode(string $isoCountryCode): self
    {
        return new self($isoCountryCode);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateIsoCountryCode(string $isoCountryCode): void
    {
        if (false === (bool) preg_match('#^[A-Z]{3}$#', $isoCountryCode)) {
            throw new InvalidArgumentException('ISO country code should contain 3 capital letters');
        }
    }
}
