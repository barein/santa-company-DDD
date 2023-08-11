<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api;

use App\Shared\Domain\Exception\AttributeParamValidationException;
use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\Domain\Exception\QueryParamValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\Ulid;

final class UlidResolver implements ValueResolverInterface
{
    /**
     * @throws AttributeParamValidationException|QueryParamValidationException
     *
     * @return iterable<Ulid>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== Ulid::class) {
            return [];
        }

        $ulid = $request->query->get('id') ?? $request->attributes->get('id');

        try {
            if (!\is_string($ulid)) {
                throw new InvalidArgumentException('ulid should be a string');
            }

            yield Ulid::fromString($ulid);
        } catch (\Throwable $exception) {
            if ($request->query->get('ulid') !== null) {
                throw new QueryParamValidationException($exception->getMessage());
            }

            throw new AttributeParamValidationException($exception->getMessage());
        }
    }
}
