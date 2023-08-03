<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateLetter\Application\Command;

use App\LetterProcessing\Shared\Domain\ChildRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateLetterHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    public function __invoke(CreateLetter $command): void
    {
        $child = $this->childRepository->get($command->getChildId());

        $child->sentLetter(receivedOn: $command->getReceivingDate(), from: $command->getSenderAddress());
    }
}
