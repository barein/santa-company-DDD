<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\HttpStatusCode;

class UnexpectedException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'UNEXPECTED_ERROR';

    public function __construct(
        string $message,
        string $code = self::DEFAULT_ERROR_CODE,
        HttpStatusCode $httpCode = HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR,
    ) {
        parent::__construct($httpCode, $message, $code);
    }
}
