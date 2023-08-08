<?php

declare(strict_types=1);

namespace App\LetterProcessing\CreateGiftRequest\Application\Command;

use App\LetterProcessing\Shared\Domain\ChildRepositoryInterface;
use App\LetterProcessing\Shared\Domain\GiftAlreadyRequestedInLetterException;
use App\LetterProcessing\Shared\Domain\MaximumNumberOfGiftRequestPerLetterReachedException;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
class CreateGiftRequestHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    /**
     * @throws MaximumNumberOfGiftRequestPerLetterReachedException
     * @throws GiftAlreadyRequestedInLetterException
     * @throws NotFoundException
     */
    public function __invoke(CreateGiftRequest $command): void
    {
        $child = $this->childRepository->get(new Ulid($command->childId));

        $child->requestsAGift(new Ulid($command->giftRequestId), new Ulid($command->letterId), $command->giftName);
    }
}
