<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api;

use App\Shared\UserInterface\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class DtoValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * @throws PayloadValidationException
     *
     * @return iterable<object>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (0 === preg_match('/^App\\\[a-zA-Z\\\]+UserInterface\\\[a-zA-Z\\\]+Dto/', (string) $argument->getType())) {
            return [];
        }

        try {
            /** @var object $dto */
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                (string) $argument->getType(),
                'json'
            );
        } catch (\Throwable $exception) {
            throw new BadRequestException('Payload error : ' . $exception->getMessage());
        }

        $constraints = $this->validator->validate($dto);

        $errors = [];
        /** @var ConstraintViolationInterface $constraint */
        foreach ($constraints as $constraint) {
            $errors[] = sprintf('[%s] %s', $constraint->getPropertyPath(), $constraint->getMessage());
        }

        if (\count($errors) > 0) {
            throw new PayloadValidationException('Payload validation errors: ' . implode(' ', $errors));
        }

        yield $dto;
    }
}
