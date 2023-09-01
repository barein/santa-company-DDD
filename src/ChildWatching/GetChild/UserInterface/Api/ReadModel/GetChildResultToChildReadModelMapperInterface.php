<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\UserInterface\Api\ReadModel;

use App\ChildWatching\GetChild\Application\Query\GetChildResult;
use App\ChildWatching\Shared\UserInterface\Api\ReadModel\ChildReadModelInterface;
use App\Shared\UserInterface\Api\ReadModel\VersionAwareInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface GetChildResultToChildReadModelMapperInterface extends VersionAwareInterface
{
    public function map(GetChildResult $getChildResult): ChildReadModelInterface;
}
