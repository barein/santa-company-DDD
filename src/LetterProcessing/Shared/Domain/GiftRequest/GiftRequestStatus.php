<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Domain\GiftRequest;

enum GiftRequestStatus: string
{
    case GRANTED = 'GRANTED';
    case DECLINED = 'DECLINED';
    case PENDING = 'PENDING';
}
