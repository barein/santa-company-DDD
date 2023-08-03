<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Uid\Ulid;

interface ChildRepositoryInterface
{
    public function add(Child $child): void;

    /**
     * @throws NotFoundException
     */
    public function get(Ulid $id): Child;
}
