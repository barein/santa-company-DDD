<?php

declare(strict_types=1);

namespace App\ChildWatching\ReportChildAction\UserInterface\Http;

use App\ChildWatching\Shared\Domain\ActionType;
use App\ChildWatching\Shared\Infrastructure\ValidationConstraint\ActionDescriptionConstraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\DateTime;

class ReportChildActionDto
{
    #[DateTime(format: 'Y-m-d')]
    public string $dateTime;

    #[ActionDescriptionConstraint]
    public string $description;

    #[Choice(choices: [ActionType::GOOD->value, ActionType::BAD->value])]
    public string $type;
}
