<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Infrastructure\ValidationConstraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsoCountryCodeConstraint extends Constraint
{
}
