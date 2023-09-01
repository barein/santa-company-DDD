<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\UserInterface\Api\ReadModel\V1_0_0;

use App\ChildWatching\Shared\UserInterface\Api\ReadModel\ChildReadModelInterface;

class ChildReadModel implements ChildReadModelInterface
{
    public string $id;

    public int $numberOfGoodActions;

    public int $numberOfBadActions;
}
