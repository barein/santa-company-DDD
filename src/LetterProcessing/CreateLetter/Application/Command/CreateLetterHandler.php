<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateLetter\Application\Command;

use App\LetterProcessing\Shared\Domain\ChildRepositoryInterface;
use App\LetterProcessing\Shared\Domain\Letter;
use App\LetterProcessing\Shared\Domain\LetterAlreadySentThisYearException;
use App\Shared\Domain\Address;
use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\Domain\Exception\LogicException;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
class CreateLetterHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     * @throws LetterAlreadySentThisYearException
     * @throws NotFoundException
     * @throws LogicException
     */
    public function __invoke(CreateLetter $command): void
    {
        $child = $this->childRepository->get(new Ulid($command->childId));

        $senderAddress = Address::from(
            $command->senderStreetNumber,
            $command->senderStreet,
            $command->senderCity,
            $command->senderZipCode,
            $command->senderIsoCountryCode,
        );

        $child->sentLetter(
            letterId: new Ulid($command->letterId),
            receivedOn: $this->getReceivingDate($command),
            from: $senderAddress
        );
    }

    /**
     * @throws LogicException
     */
    private function getReceivingDate(CreateLetter $command): \DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat(Letter::RECEIVING_DATE_FORMAT, $command->receivingDate);

        if ($date === false) {
            throw new LogicException(sprintf(
                'DateTime format should be %s, %s given',
                Letter::RECEIVING_DATE_FORMAT,
                $command->receivingDate,
            ));
        }

        return $date;
    }
}
