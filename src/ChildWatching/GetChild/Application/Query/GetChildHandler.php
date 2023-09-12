<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\Application\Query;

use App\ChildWatching\Shared\Domain\ActionRepositoryInterface;
use App\ChildWatching\Shared\Domain\ChildRepositoryInterface;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetChildHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
        private ActionRepositoryInterface $actionRepository
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function __invoke(GetChild $query): GetChildResult
    {
        $child = $this->childRepository->get($query->childId);
        $actions = $this->actionRepository->getActionsOfChildThisYear($child);

        return new GetChildResult($child, $actions);
    }
}
