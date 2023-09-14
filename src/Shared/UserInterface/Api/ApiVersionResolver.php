<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api;

use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\UserInterface\Api\ReadModel\ApiVersion;
use App\Shared\UserInterface\Exception\QueryParamValidationException;
use App\Shared\UserInterface\Exception\RouteParamValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class ApiVersionResolver implements ValueResolverInterface
{
    /**
     * @throws QueryParamValidationException
     *
     * @return iterable<ApiVersion>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== ApiVersion::class) {
            return [];
        }

        try {
            yield ApiVersion::fromString(\strval($request->attributes->get('version')));
        } catch (InvalidArgumentException $exception) {
            throw new RouteParamValidationException($exception->getMessage());
        }
    }
}
