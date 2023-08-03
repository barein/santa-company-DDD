<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateLetter\Application\Command;

use App\LetterProcessing\Shared\Domain\Letter;
use App\Shared\Domain\Address;
use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\Domain\Exception\LogicException;
use App\Shared\Infrastructure\ValidationConstraint\IsoCountryCodeConstraint;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

readonly class CreateLetter
{
    public function __construct(
        #[UlidConstraint]
        public string $childId,

        #[DateTime(format: Letter::RECEIVING_DATE_FORMAT)]
        public string $receivingDate,

        public int $senderStreetNumber,

        #[NotBlank]
        public string $senderStreet,

        #[NotBlank]
        public string $senderCity,

        public int $senderZipCode,

        #[IsoCountryCodeConstraint]
        public string $senderIsoCountryCode,
    ) {
    }

    public function getChildId(): Ulid
    {
        return new Ulid($this->childId);
    }

    /**
     * @throws LogicException
     */
    public function getReceivingDate(): \DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat(Letter::RECEIVING_DATE_FORMAT, $this->receivingDate);

        if ($date === false) {
            throw new LogicException(sprintf(
                'DateTime format should be %s, %s given',
                Letter::RECEIVING_DATE_FORMAT,
                $this->receivingDate,
            ));
        }

        return $date;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getSenderAddress(): Address
    {
        return Address::from(
            $this->senderStreetNumber,
            $this->senderStreet,
            $this->senderCity,
            $this->senderZipCode,
            $this->senderIsoCountryCode,
        );
    }
}
