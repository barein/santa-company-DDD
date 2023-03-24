<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

class LogicException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'LOGIC_EXCEPTION';

    public function __construct(string $message, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct(HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR, $message, $code);
    }
}
