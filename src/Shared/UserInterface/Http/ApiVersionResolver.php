<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Http;

use App\Shared\Domain\ApiVersion;
use App\Shared\Domain\Exception\QueryParamValidationException;
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

        $version = $request->query->getInt('v');

        if ($version === 0) {
            throw new QueryParamValidationException('API version query parameter is not provided');
        }

        try {
            yield ApiVersion::fromInt($version);
        } catch (\Throwable $exception) {
            throw new QueryParamValidationException($exception->getMessage());
        }
    }
}
