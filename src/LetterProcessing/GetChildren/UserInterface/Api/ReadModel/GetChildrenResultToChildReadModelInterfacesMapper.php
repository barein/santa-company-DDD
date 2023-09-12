<?php

declare(strict_types=1);

namespace App\LetterProcessing\GetChildren\UserInterface\Api\ReadModel;

use App\LetterProcessing\GetChildren\Application\Query\GetChildrenResult;
use App\LetterProcessing\Shared\UserInterface\Api\ReadModel\ChildEntityToChildReadModelInterfaceMapper;
use App\LetterProcessing\Shared\UserInterface\Api\ReadModel\ChildReadModelInterface;
use App\Shared\UserInterface\Api\ReadModel\ApiVersion;

readonly class GetChildrenResultToChildReadModelInterfacesMapper
{
    public function __construct(
        private ChildEntityToChildReadModelInterfaceMapper $childEntityToChildReadModelInterfaceMapper,
    ) {
    }

    /**
     * @return ChildReadModelInterface[]
     */
    public function map(GetChildrenResult $getChildrenResult, ApiVersion $version): array
    {
        $childReadModels = [];

        foreach ($getChildrenResult->children as $child) {
            $childReadModels[] = $this->childEntityToChildReadModelInterfaceMapper->map($child, $version);
        }

        return $childReadModels;
    }
}
