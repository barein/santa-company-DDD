<?php

declare(strict_types=1);

namespace App\ChildWatching\ReportChildAction\UserInterface\Http\Dto;

use App\ChildWatching\Shared\Domain\ActionType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Ulid;

class ChildActionDto
{
    #[NotNull]
    #[Ulid]
    public string $childUlid;

    #[NotNull]
    #[DateTime(format: 'Y-m-d')]
    public string $dateTime;

    #[NotBlank]
    public string $description;

    #[NotNull]
    #[Choice(choices: [ActionType::GOOD->value, ActionType::BAD->value])]
    public string $type;
}
