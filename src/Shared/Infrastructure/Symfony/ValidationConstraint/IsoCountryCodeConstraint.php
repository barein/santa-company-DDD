<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\ValidationConstraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsoCountryCodeConstraint extends Constraint
{
}
