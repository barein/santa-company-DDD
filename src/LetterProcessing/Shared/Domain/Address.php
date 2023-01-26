<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Exception\InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Address
{
    #[ORM\Column]
    private int $number;

    #[ORM\Column(length: 255)]
    private string $street;

    #[ORM\Column(length: 255)]
    private string $city;

    #[ORM\Column]
    private int $zipCode;

    #[ORM\Embedded(class: IsoCountryCode::class, columnPrefix: false)]
    private IsoCountryCode $isoCountryCode;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(int $number, string $street, string $city, int $zipCode, string $isoCountryCode)
    {
        $this->number = $number;
        $this->street = $street;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->isoCountryCode = IsoCountryCode::fromCode($isoCountryCode);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function from(int $number, string $street, string $city, int $zipCode, string $isoCountryCode): self
    {
        return new self($number, $street, $city, $zipCode, $isoCountryCode);
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getZipCode(): int
    {
        return $this->zipCode;
    }

    public function getIsoCountryCode(): IsoCountryCode
    {
        return $this->isoCountryCode;
    }

    public function equal(self $address): bool
    {
        return $this->getNumber() === $address->getNumber() &&
            $this->getStreet() === $address->getStreet() &&
            $this->getCity() === $address->getCity() &&
            $this->getZipCode() === $address->getZipCode() &&
            $this->getIsoCountryCode()->getValue() === $address->getIsoCountryCode()->getValue()
        ;
    }
}
