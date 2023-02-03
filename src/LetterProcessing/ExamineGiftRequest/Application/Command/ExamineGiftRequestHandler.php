<?php

declare(strict_types=1);

namespace App\LetterProcessing\ExamineGiftRequest\Application\Command;

use App\LetterProcessing\Shared\Domain\ChildGiftRequestExaminerInterface;
use App\LetterProcessing\Shared\Domain\ChildRepositoryInterface;
use App\LetterProcessing\Shared\Domain\ChildRequestedAGift;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ExamineGiftRequestHandler
{
    public function __construct(
        private readonly ChildGiftRequestExaminerInterface $childGiftRequestExaminer,
        private readonly ChildRepositoryInterface $childRepository,
    ) {
    }

    public function __invoke(ChildRequestedAGift $event): void
    {
        $child = $this->childRepository->getByUlid($event->getChildUlid());
        $this->childGiftRequestExaminer->examine($child, $event->getLetterUlid(), $event->getGiftRequestUlid());
    }
}
