<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

interface ChildWatchingGatewayInterface
{
    public function getChildSantaList(Child $child): SantaList;
}
