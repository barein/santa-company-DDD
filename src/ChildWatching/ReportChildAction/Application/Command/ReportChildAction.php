<?php

declare(strict_types=1);

namespace App\ChildWatching\ReportChildAction\Application\Command;

use App\ChildWatching\Shared\Domain\Action;
use App\ChildWatching\Shared\Domain\ActionType;
use App\ChildWatching\Shared\Infrastructure\Symfony\ValidationConstraint\ActionDescriptionConstraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Ulid;

readonly class ReportChildAction
{
    public function __construct(
        #[Ulid]
        public string $actionId,

        #[Ulid]
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
