<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain\Letter;

use App\Shared\Domain\Exception\LogicException;

class LetterAlreadySentThisYearException extends LogicException
{
    /** @phpstan-ignore-next-line */
    private const DEFAULT_ERROR_CODE = 'LETTER_ALREADY_SENT_THIS_YEAR';

    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
