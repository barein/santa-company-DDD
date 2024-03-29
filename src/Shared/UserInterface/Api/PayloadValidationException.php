<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api;

use App\Shared\Domain\Exception\AbstractBaseException;
use App\Shared\Domain\Exception\HttpStatusCode;

final class PayloadValidationException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'PAYLOAD_VALIDATION_ERROR';

    public function __construct(string $message, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct(HttpStatusCode::UNPROCESSABLE_ENTITY, $message, $code);
    }
}
