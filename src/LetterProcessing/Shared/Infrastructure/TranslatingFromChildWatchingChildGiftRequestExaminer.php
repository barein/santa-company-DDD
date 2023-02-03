<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Infrastructure;

use App\LetterProcessing\Shared\Domain\Child;
use App\LetterProcessing\Shared\Domain\ChildGiftRequestExaminerInterface;
use App\LetterProcessing\Shared\Domain\ChildWatchingGatewayInterface;
use Symfony\Component\Uid\Ulid;

class TranslatingFromChildWatchingChildGiftRequestExaminer implements ChildGiftRequestExaminerInterface
{
    public function __construct(
        private readonly ChildWatchingGatewayInterface $childWatchingGateway,
    ) {
    }

    public function examine(Child $child, Ulid $letterUlid, Ulid $giftRequestUlid): void
    {
        $childSantaList = $this->childWatchingGateway->getChildList($child);

        $child->isOnSantaListForGiftRequest(
            santaList: $childSantaList,
            giftRequestUlid: $giftRequestUlid,
            letterUlid: $letterUlid,
        );
    }
}
