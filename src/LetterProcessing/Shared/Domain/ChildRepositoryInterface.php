<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\Uid\Ulid;

interface ChildRepositoryInterface
{
    public function add(Child $child): void;

    /**
     * @throws NotFoundException
     */
    public function getByUlid(Ulid $childUlid): Child;
}
