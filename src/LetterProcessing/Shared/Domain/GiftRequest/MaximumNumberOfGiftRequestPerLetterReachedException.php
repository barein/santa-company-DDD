<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain\GiftRequest;

use App\Shared\Domain\Exception\LogicException;

class MaximumNumberOfGiftRequestPerLetterReachedException extends LogicException
{
    /** @phpstan-ignore-next-line */
    private const DEFAULT_ERROR_CODE = 'MAXIMUM_NUMBER_OF_GIFT_REQUEST_PER_LETTER';

    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
