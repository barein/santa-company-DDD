<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateChild\UserInterface\Http;

use App\Shared\Infrastructure\ValidationConstraint\IsoCountryCodeConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateChildDto
{
    #[NotBlank]
    public string $firstName;

    #[NotBlank]
    public string $lastName;

    public int $streetNumber;

    #[NotBlank]
    public string $street;

    #[NotBlank]
    public string $city;

    public int $zipCode;

    #[IsoCountryCodeConstraint]
    public string $isoCountryCode;
}
