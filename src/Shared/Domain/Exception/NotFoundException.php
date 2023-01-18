<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\HttpStatusCode;

class NotFoundException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'NOT_FOUND';

    public function __construct(string $message, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct(HttpStatusCode::HTTP_NOT_FOUND, $message, $code);
    }
}
