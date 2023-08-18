<?php

declare(strict_types=1);

namespace App\ChildWatching\UpdateChildAddress\Application\Command;

use App\ChildWatching\Shared\Domain\ChildRepositoryInterface;
use App\LetterProcessing\Shared\Domain\Child\ChildMoved;
use App\Shared\Domain\Address;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
readonly class UpdateChildAddressHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function __invoke(ChildMoved $event): void
    {
        $child = $this->childRepository->get(new Ulid($event->childId));

        $child->movedTo(Address::from(
            number: $event->streetNumber,
            street: $event->streetName,
            city: $event->city,
            zipCode: $event->zipCode,
            isoCountryCode: $event->isoCountryCode,
        ));
    }
}
