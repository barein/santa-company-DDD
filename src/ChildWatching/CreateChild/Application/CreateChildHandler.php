<?php

declare(strict_types=1);

namespace App\ChildWatching\CreateChild\Application;

use App\ChildWatching\Shared\Domain\Child;
use App\ChildWatching\Shared\Domain\ChildAlreadyCreatedException;
use App\ChildWatching\Shared\Domain\ChildRepositoryInterface;
use App\LetterProcessing\Shared\Domain\ChildWasCreated;
use App\Shared\Domain\Address;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateChildHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    public function __invoke(ChildWasCreated $event): void
    {
        $child = null;
        try {
            $child = $this->childRepository->getByUlid($event->getChildUlid());
        } catch (NotFoundException) {
        }

        if ($child !== null) {
            throw new ChildAlreadyCreatedException();
        }

        $child = new Child(
            $event->getChildUlid(),
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
