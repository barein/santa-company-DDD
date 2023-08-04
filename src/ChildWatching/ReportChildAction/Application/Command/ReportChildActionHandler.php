<?php

declare(strict_types=1);

namespace App\ChildWatching\ReportChildAction\Application\Command;

use App\ChildWatching\Shared\Domain\Action;
use App\ChildWatching\Shared\Domain\ActionDescription;
use App\ChildWatching\Shared\Domain\ActionRepositoryInterface;
use App\ChildWatching\Shared\Domain\ActionType;
use App\ChildWatching\Shared\Domain\ChildRepositoryInterface;
use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\Domain\Exception\LogicException;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Ulid;

#[AsMessageHandler]
readonly class ReportChildActionHandler
{
    public function __construct(
        private ChildRepositoryInterface $childRepository,
        private ActionRepositoryInterface $actionRepository,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws LogicException
     */
    public function __invoke(ReportChildAction $command): void
    {
        $child = $this->childRepository->get(new Ulid($command->childId));

        $action = $child->madeAction(
            $this->getActionDateTime($command),
            ActionDescription::fromString($command->description),
            ActionType::from($command->type),
        );

        $this->actionRepository->add($action);
    }

    /**
     * @throws LogicException
     */
    private function getActionDateTime(ReportChildAction $command): \DateTimeImmutable
    {
        $dateTime = \DateTimeImmutable::createFromFormat(Action::DATE_TIME_FORMAT, $command->dateTime);

        if ($dateTime === false) {
            throw new LogicException(sprintf(
                'DateTime format should be %s, %s given',
                Action::DATE_TIME_FORMAT,
                $command->dateTime,
            ));
        }

        return $dateTime;
    }
}
