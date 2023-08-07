<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

class BadRequestException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'BAD_REQUEST';

    public function __construct(
        string $message,
        string $code = self::DEFAULT_ERROR_CODE,
        HttpStatusCode $httpStatusCode = HttpStatusCode::HTTP_BAD_REQUEST,
    ) {
        parent::__construct($httpStatusCode, $message, $code);
    }
}
