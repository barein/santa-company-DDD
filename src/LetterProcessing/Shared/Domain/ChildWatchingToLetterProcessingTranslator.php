<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

final class ChildWatchingToLetterProcessingTranslator
{
    public function getSantaListForChildBasedOnItsActions(int $numberOfGoodActions, int $numberOfBadActions): SantaList
    {
        return ($numberOfGoodActions >= $numberOfBadActions) ? SantaList::GOOD : SantaList::BAD;
    }
}
