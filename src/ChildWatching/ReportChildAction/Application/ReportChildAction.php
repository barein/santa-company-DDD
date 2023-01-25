<?php

declare(strict_types=1);

namespace App\ChildWatching\ReportChildAction\Application;

use App\ChildWatching\Shared\Domain\ActionDescription;
use App\ChildWatching\Shared\Domain\ActionType;
use App\ChildWatching\Shared\Infrastructure\ValidationConstraint\ActionDescriptionConstraint;
use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\Domain\Exception\LogicException;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Ulid as UlidConstraint;

readonly class ReportChildAction
{
    public function __construct(
        #[UlidConstraint]
        public string $childUlid,

        #[DateTime(format: 'Y-m-d')]
        public string $dateTime,

        #[ActionDescriptionConstraint]
        public string $description,

        #[Choice(choices: [ActionType::GOOD->value, ActionType::BAD->value])]
        public string $type,
    ) {
    }

    public function getChildUlid(): Ulid
    {
        return new Ulid($this->childUlid);
    }

    public function getDateTime(): \DateTimeImmutable
    {
        $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d', $this->dateTime);

        if ($dateTime === false) {
            throw new LogicException(sprintf(
                "DateTime format should be 'Y-m-d', '%s' given",
                $this->dateTime,
            ));
        }

        return $dateTime;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getDescription(): ActionDescription
    {
        return ActionDescription::fromString($this->description);
    }

    public function getType(): ActionType
    {
        return ActionType::from($this->type);
    }
}
