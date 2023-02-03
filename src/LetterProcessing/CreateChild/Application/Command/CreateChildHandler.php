<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateChild\Application\Command;

use App\LetterProcessing\Shared\Domain\Child;
use App\LetterProcessing\Shared\Domain\ChildRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateChildHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    public function __invoke(CreateChild $command): void
    {
        $child = new Child(
            $command->getFirstName(),
            $command->getLastName(),
            $command->getAddress(),
        );

        $this->childRepository->add($child);
    }
}
