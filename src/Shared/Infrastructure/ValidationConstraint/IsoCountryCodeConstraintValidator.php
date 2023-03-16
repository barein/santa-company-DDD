<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ValidationConstraint;

use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\Domain\IsoCountryCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsoCountryCodeConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsoCountryCodeConstraint) {
            throw new UnexpectedTypeException($constraint, IsoCountryCodeConstraint::class);
        }

        if ($value === null) {
            return;
        }

        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        try {
            IsoCountryCode::fromCode($value);
        } catch (InvalidArgumentException $exception) {
            $this->context->buildViolation($exception->getMessage())->addViolation();
        }
    }
}
