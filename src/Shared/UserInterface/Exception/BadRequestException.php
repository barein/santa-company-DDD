<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Exception;

use App\Shared\Domain\Exception\AbstractBaseException;
use App\Shared\Domain\Exception\HttpStatusCode;

class BadRequestException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'BAD_REQUEST';

    public function __construct(
        string $message,
        string $code = self::DEFAULT_ERROR_CODE,
    ) {
        parent::__construct(HttpStatusCode::HTTP_BAD_REQUEST, $message, $code);
    }
}
