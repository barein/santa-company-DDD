<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

use Symfony\Component\Uid\Ulid;

interface ActionRepositoryInterface
{
    public function add(Action $action): void;

    /**
     * @return array<Action>
     */
    public function getActionsOfChildThisYear(Ulid $childUlid): array;
}
