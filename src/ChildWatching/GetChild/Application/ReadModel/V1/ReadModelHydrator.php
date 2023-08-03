<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\Application\ReadModel\V1;

use App\ChildWatching\GetChild\Application\ReadModel\GetChildReadModelDto;
use App\ChildWatching\Shared\Application\ReadModel\V1\ChildReadModel;
use App\ChildWatching\Shared\Domain\Action;
use App\ChildWatching\Shared\Domain\ActionType;
use App\Shared\Application\ReadModelHydratorInterface;
use Ds\Vector;

class ReadModelHydrator implements ReadModelHydratorInterface
{
    /**
     * @param GetChildReadModelDto $dto
     */
    public function hydrate(object $dto): ChildReadModel
    {
        $childReadModel = new ChildReadModel();

        $childReadModel->id = (string) $dto->child->getId();
        $childReadModel->numberOfBadActions = (new Vector($dto->currentYearActions))
            ->filter(fn (Action $action) => $action->getType() === ActionType::BAD)
            ->count();
        $childReadModel->numberOfGoodActions = (new Vector($dto->currentYearActions))
            ->filter(fn (Action $action) => $action->getType() === ActionType::GOOD)
            ->count();

        return $childReadModel;
    }
}
