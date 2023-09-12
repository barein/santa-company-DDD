<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\UserInterface\Api\ReadModel\V1_0_0;

use App\ChildWatching\GetChild\Application\Query\GetChildResult;
use App\ChildWatching\GetChild\UserInterface\Api\ReadModel\GetChildResultToChildReadModelMapperInterface;
use App\ChildWatching\Shared\Domain\ActionReadInterface;
use App\ChildWatching\Shared\Domain\ActionType;
use App\ChildWatching\Shared\UserInterface\Api\ReadModel\V1_0_0\ChildReadModel;
use App\Shared\UserInterface\Api\ReadModel\ApiVersion;
use Ds\Vector;

class GetChildResultToChildReadModelMapper implements GetChildResultToChildReadModelMapperInterface
{
    public function getVersion(): ApiVersion
    {
        return ApiVersion::fromInt(100);
    }

    public function map(GetChildResult $getChildResult): ChildReadModel
    {
        $childReadModel = new ChildReadModel();

        $childReadModel->id = (string) $getChildResult->child->getId();

        $childReadModel->numberOfBadActions = (new Vector($getChildResult->currentYearActions))
            ->filter(fn (ActionReadInterface $action) => $action->getType() === ActionType::BAD)
            ->count();

        $childReadModel->numberOfGoodActions = (new Vector($getChildResult->currentYearActions))
            ->filter(fn (ActionReadInterface $action) => $action->getType() === ActionType::GOOD)
            ->count();

        return $childReadModel;
    }
}
