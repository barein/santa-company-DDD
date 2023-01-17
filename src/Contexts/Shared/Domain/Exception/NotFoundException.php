<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Domain\Exception;

use App\Contexts\Shared\Domain\HttpCode;

class NotFoundException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'NOT_FOUND';

    public function __construct(string $message, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct(HttpCode::HTTP_NOT_FOUND(), $message, $code);
    }
}
