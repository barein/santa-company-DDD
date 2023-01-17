<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Domain\Exception;


use App\Contexts\Shared\Domain\HttpCode;

class BadRequestException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'BAD_REQUEST';

    public function __construct(string $message, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct(HttpCode::HTTP_BAD_REQUEST(), $message, $code);
    }
}
