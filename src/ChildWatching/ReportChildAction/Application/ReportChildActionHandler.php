<?php

declare(strict_types=1);

namespace App\ChildWatching\ReportChildAction\Application;

use App\ChildWatching\Shared\Domain\ActionRepositoryInterface;
use App\ChildWatching\Shared\Domain\ChildRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ReportChildActionHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
        private ActionRepositoryInterface $actionRepository,
    ) {
    }

    public function __invoke(ReportChildAction $command): void
    {
        $child = $this->childRepository->getByUlid($command->getChildUlid());

        $action = $child->madeAction(
            $command->getDateTime(),
            $command->getDescription(),
            $command->getType(),
        );

        $this->actionRepository->add($action);
    }
}
