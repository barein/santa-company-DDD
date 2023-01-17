<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Application;

interface ReadModelHydratorInterface
{
    public function hydrate(object $dto): object;
}
