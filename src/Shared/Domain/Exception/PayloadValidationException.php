<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

final class PayloadValidationException extends BadRequestException
{
    private const DEFAULT_ERROR_CODE = 'PAYLOAD_VALIDATION_ERROR';

    public function __construct(string $message, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct($message, $code, HttpStatusCode::UNPROCESSABLE_ENTITY);
    }
}
