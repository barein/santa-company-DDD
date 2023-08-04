<?php

declare(strict_types=1);

namespace App\LetterProcessing\ExamineGiftRequest\Application\Command;

use App\LetterProcessing\Shared\Domain\ChildGiftRequestExaminerInterface;
use App\LetterProcessing\Shared\Domain\ChildRepositoryInterface;
use App\LetterProcessing\Shared\Domain\ChildRequestedAGift;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
readonly class ExamineGiftRequestHandler
{
    public function __construct(
        private ChildGiftRequestExaminerInterface $childGiftRequestExaminer,
        private ChildRepositoryInterface $childRepository,
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function __invoke(ChildRequestedAGift $event): void
    {
        $child = $this->childRepository->get(new Ulid($event->childId));

        $this->childGiftRequestExaminer->examine($child, new Ulid($event->letterId), new Ulid($event->giftRequestId));
    }
}
