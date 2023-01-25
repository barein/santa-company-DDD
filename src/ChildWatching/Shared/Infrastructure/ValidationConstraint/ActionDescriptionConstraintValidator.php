<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Infrastructure\ValidationConstraint;

use App\ChildWatching\Shared\Domain\ActionDescription;
use App\Shared\Domain\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ActionDescriptionConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ActionDescriptionConstraint) {
            throw new UnexpectedTypeException($constraint, ActionDescriptionConstraint::class);
        }

        if ($value === null) {
            return;
        }

        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        try {
            ActionDescription::fromString($value);
        } catch (InvalidArgumentException $exception) {
            $this->context->buildViolation($exception->getMessage())->addViolation();
        }
    }
}
