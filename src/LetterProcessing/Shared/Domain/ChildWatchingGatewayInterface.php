<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Exception\ExternalDependencyFailedException;

interface ChildWatchingGatewayInterface
{
    /**
     * @throws ExternalDependencyFailedException
     */
    public function getSantaListForChild(Child $child): SantaList;
}
