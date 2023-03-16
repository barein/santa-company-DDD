<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

use App\Shared\Domain\Exception\UnexpectedException;

class ChildAlreadyCreatedException extends UnexpectedException
{
    /** @phpstan-ignore-next-line */
    private const DEFAULT_ERROR_CODE = 'CHILD_ALREADY_CREATED_EXCEPTION';

    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }
}
