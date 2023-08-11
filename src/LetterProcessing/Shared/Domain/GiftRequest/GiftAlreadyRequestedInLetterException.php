<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain\GiftRequest;

use App\Shared\Domain\Exception\LogicException;

class GiftAlreadyRequestedInLetterException extends LogicException
{
    /** @phpstan-ignore-next-line */
    private const DEFAULT_ERROR_CODE = 'GIFT_ALREADY_REQUESTED_IN_LETTER';

    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
