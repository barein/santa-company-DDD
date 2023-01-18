<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Http;

use App\Shared\Domain\Exception\AttributeParamValidationException;
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

        yield UlidRequestExtractor::getUlidFromRequest($request, 'ulid');
    }
}
