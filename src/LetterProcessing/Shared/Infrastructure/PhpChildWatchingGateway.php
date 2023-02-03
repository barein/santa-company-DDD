<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Infrastructure;

use App\ChildWatching\Shared\UserInterface\ChildWatchingService;
use App\LetterProcessing\Shared\Domain\Child;
use App\LetterProcessing\Shared\Domain\ChildWatchingGatewayInterface;
use App\LetterProcessing\Shared\Domain\SantaList;
use App\Shared\Domain\ApiVersion;

class PhpChildWatchingGateway implements ChildWatchingGatewayInterface
{
    public function __construct(
        private readonly ChildWatchingService $childWatchingService,
    ) {
    }

    public function getChildList(Child $child): SantaList
    {
        $childRepresentation = $this->childWatchingService->getChild($child->getUlid(), ApiVersion::fromInt(1));

        /** @var array<string, mixed> $arrayChildRepresentation */
        $arrayChildRepresentation = json_decode(json: $childRepresentation, associative: true, flags: JSON_THROW_ON_ERROR);

        return (new ChildWatchingToLetterProcessingTranslator())->getChildListBasedOnItsActions(
            numberOfGoodActions: \intval($arrayChildRepresentation['numberOfGoodActions']),
            numberOfBadActions: \intval($arrayChildRepresentation['numberOfBadActions'])
        );
    }
}
