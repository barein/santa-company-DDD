<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Http;

use App\Shared\Domain\Exception\AttributeParamValidationException;
use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\Domain\Exception\QueryParamValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Ulid;

class UlidRequestExtractor
{
    public static function getUlidFromRequest(Request $request, string $ulidParamName): Ulid
    {
        $ulid = $request->query->get($ulidParamName) ?? $request->attributes->get($ulidParamName);

        try {
            if (!\is_string($ulid)) {
                throw new InvalidArgumentException("$ulidParamName should be a string");
            }

            return Ulid::fromString($ulid);
        } catch (\Throwable $exception) {
            if ($request->query->get($ulidParamName) !== null) {
                throw new QueryParamValidationException($exception->getMessage());
            }
            throw new AttributeParamValidationException($exception->getMessage());
        }
    }
}
