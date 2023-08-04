<?php

declare(strict_types=1);

namespace App\ChildWatching\ReportChildAction\Application\Command;

use App\ChildWatching\Shared\Domain\Action;
use App\ChildWatching\Shared\Domain\ActionType;
use App\ChildWatching\Shared\Infrastructure\ValidationConstraint\ActionDescriptionConstraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

readonly class ReportChildAction
{
    public function __construct(
        #[UlidConstraint]
        public string $childId,

        #[DateTime(format: Action::DATE_TIME_FORMAT)]
        public string $dateTime,

        #[ActionDescriptionConstraint]
        public string $description,

        #[Choice(choices: [ActionType::GOOD->value, ActionType::BAD->value])]
        public string $type,
    ) {
    }
}
