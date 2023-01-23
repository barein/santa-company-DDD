<?php

declare(strict_types=1);

namespace App\Shared\Application;

interface ReadModelHydratorInterface
{
    public function hydrate(object $dto): object;
}
