<?php

declare(strict_types=1);

namespace App\LetterProcessing\ExamineGiftRequest\Application\Command;

use App\LetterProcessing\Shared\Domain\Child\ChildRepositoryInterface;
use App\LetterProcessing\Shared\Domain\GiftRequest\ChildRequestedAGift;
use App\LetterProcessing\Shared\Domain\GiftRequest\GiftRequestExaminer;
use App\Shared\Domain\Exception\ExternalDependencyFailedException;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
readonly class ExamineGiftRequestHandler
{
    public function __construct(
        private GiftRequestExaminer $giftRequestExaminer,
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    /**
     * @throws NotFoundException
     * @throws ExternalDependencyFailedException
     */
    public function __invoke(ChildRequestedAGift $event): void
    {
        $child = $this->childRepository->get(new Ulid($event->childId));

        $this->giftRequestExaminer->examine($child, new Ulid($event->letterId), new Ulid($event->giftRequestId));
    }
}
