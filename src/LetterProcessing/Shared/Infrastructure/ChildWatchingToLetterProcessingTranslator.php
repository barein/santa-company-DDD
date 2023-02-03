<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Infrastructure;

use App\LetterProcessing\Shared\Domain\SantaList;

final class ChildWatchingToLetterProcessingTranslator
{
    public function getChildListBasedOnItsActions(int $numberOfGoodActions, int $numberOfBadActions): SantaList
    {
        return $numberOfGoodActions > $numberOfBadActions ? SantaList::GOOD : SantaList::BAD;
    }
}
