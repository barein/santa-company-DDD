<?php

declare(strict_types=1);

namespace App\ChildWatching\CreateChild\Application\Command;

use App\ChildWatching\Shared\Domain\Child;
use App\ChildWatching\Shared\Domain\ChildAlreadyCreatedException;
use App\ChildWatching\Shared\Domain\ChildRepositoryInterface;
use App\LetterProcessing\Shared\Domain\Child\NewChildSentLetter;
use App\Shared\Domain\Address;
use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
readonly class CreateChildHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    /**
     * @throws ChildAlreadyCreatedException
     * @throws InvalidArgumentException
     */
    public function __invoke(NewChildSentLetter $event): void
    {
        $child = null;
        try {
            $child = $this->childRepository->get(new Ulid($event->childId));
        } catch (NotFoundException) {
        }

        if ($child !== null) {
            throw new ChildAlreadyCreatedException();
        }

        $child = new Child(
            new Ulid($event->childId),
            $event->firstName,
            $event->lastName,
            Address::from(
                number: $event->streetNumber,
                street: $event->streetName,
                city: $event->city,
                zipCode: $event->zipCode,
                isoCountryCode: $event->isoCountryCode,
            )
        );

        $this->childRepository->add($child);
    }
}
