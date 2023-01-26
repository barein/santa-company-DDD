<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain;

use App\Shared\Domain\Exception\LogicException;

class LetterAlreadySentThisYearException extends LogicException
{
    private const DEFAULT_ERROR_CODE = 'LETTER_ALREADY_SENT_THIS_YEAR';

    /** @phpstan-ignore-line */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
