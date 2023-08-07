<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateLetter\UserInterface\Api;

use App\LetterProcessing\Shared\Domain\Letter;
use App\Shared\Infrastructure\Symfony\ValidationConstraint\IsoCountryCodeConstraint;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateLetterDto
{
    #[DateTime(format: Letter::RECEIVING_DATE_FORMAT)]
    public string $receivingDate;

    #[GreaterThan(0)]
    public int $senderStreetNumber;

    #[NotBlank]
    public string $senderStreet;

    #[NotBlank]
    public string $senderCity;

    public int $senderZipCode;

    #[IsoCountryCodeConstraint]
    public string $senderIsoCountryCode;
}
