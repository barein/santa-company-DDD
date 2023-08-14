<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Application\ReadModel\Child\V1;

class ChildReadModel
{
    public string $id;

    public int $numberOfGoodActions;

    public int $numberOfBadActions;
}