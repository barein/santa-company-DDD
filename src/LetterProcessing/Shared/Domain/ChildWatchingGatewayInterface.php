<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

interface ChildWatchingGatewayInterface
{
    public function getChildList(Child $child): SantaList;
}
