<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

class ExternalDependencyFailedException extends AbstractBaseException
{
    private const DEFAULT_ERROR_CODE = 'EXTERNAL_DEPENDENCY_FAILED';

    public function __construct(int $externalStatusCode, string $code = self::DEFAULT_ERROR_CODE)
    {
        parent::__construct(
            HttpStatusCode::HTTP_BAD_GATEWAY,
            sprintf('External dependency failed with status code %d', $externalStatusCode),
            $code
        );
    }
}
