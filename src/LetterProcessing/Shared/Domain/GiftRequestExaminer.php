<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Exception\ExternalDependencyFailedException;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Uid\Ulid;

readonly class GiftRequestExaminer
{
    public function __construct(
        private ChildWatchingGatewayInterface $childWatchingGateway,
    ) {
    }

    /**
     * @throws NotFoundException
     * @throws ExternalDependencyFailedException
     */
    public function examine(Child $child, Ulid $letterId, Ulid $giftRequestId): void
    {
        $santaListForChild = $this->childWatchingGateway->getSantaListForChild($child);

        if ($santaListForChild === SantaList::GOOD) {
            $child->giftRequestGranted($giftRequestId, $letterId);

            return;
        }

        $child->giftRequestDeclined($giftRequestId, $letterId);
    }
}
