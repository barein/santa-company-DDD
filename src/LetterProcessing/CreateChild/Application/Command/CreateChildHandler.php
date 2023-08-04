<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateChild\Application\Command;

use App\LetterProcessing\Shared\Domain\Child;
use App\LetterProcessing\Shared\Domain\ChildRepositoryInterface;
use App\Shared\Domain\Address;
use App\Shared\Domain\Exception\InvalidArgumentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
class CreateChildHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(CreateChild $command): void
    {
        $address = Address::from(
            $command->streetNumber,
            $command->street,
            $command->city,
            $command->zipCode,
            $command->isoCountryCode,
        );

        $child = new Child(new Ulid($command->childId), $command->firstName, $command->lastName, $address);

        $this->childRepository->add($child);
    }
}
