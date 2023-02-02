<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateChild\Application\Command;

use App\LetterProcessing\Shared\Domain\Address;
use App\LetterProcessing\Shared\Infrastructure\ValidationConstraint\IsoCountryCodeConstraint;
use App\Shared\Domain\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraints\NotBlank;

readonly class CreateChild
{
    public function __construct(
        #[NotBlank]
        public string $firstName,

        #[NotBlank]
        public string $lastName,

        public int $streetNumber,

        #[NotBlank]
        public string $street,

        #[NotBlank]
        public string $city,

        public int $zipCode,

        #[IsoCountryCodeConstraint]
        public string $isoCountryCode,
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getAddress(): Address
    {
        return Address::from(
            $this->streetNumber,
            $this->street,
            $this->city,
            $this->zipCode,
            $this->isoCountryCode,
        );
    }
}
