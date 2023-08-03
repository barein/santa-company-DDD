<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateGiftRequest\Application\Command;

use App\LetterProcessing\Shared\Domain\ChildRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateGiftRequestHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    public function __invoke(CreateGiftRequest $command): void
    {
        $child = $this->childRepository->get($command->getChildId());

        $child->requestedAGift($command->getLetterId(), $command->getGiftName());
    }
}
