<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Uid\Ulid;

interface ChildRepositoryInterface
{
    /**
     * @throws NotFoundException
     */
    public function getByUlid(Ulid $childUlid): Child;

    public function add(Child $child): void;
}
