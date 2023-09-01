<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api\ReadModel;

use App\Shared\Domain\Exception\AbstractBaseException;
use App\Shared\Domain\Exception\HttpStatusCode;

class NoReadModelMapperFound extends AbstractBaseException
{
    public const DEFAULT_ERROR_CODE = 'NO_READ_MODEL_MAPPER_FOUND';

    public function __construct(string $sourceClass, string $destinationReadModelInterface, ApiVersion $version)
    {
        $message = sprintf(
            'No matching read model mapper was found to convert %s to %s in version %d',
            $sourceClass,
            $destinationReadModelInterface,
            $version->getValue(),
        );

        parent::__construct(
            HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR,
            $message,
            self::DEFAULT_ERROR_CODE
        );
    }
}
