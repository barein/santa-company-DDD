<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\HttpCode;

class InvalidArgumentException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'INVALID_ARGUMENT_EXCEPTION';

    public function __construct(string $message, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct(HttpCode::HTTP_INTERNAL_SERVER_ERROR(), $message, $code);
    }
}
