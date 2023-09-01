<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Infrastructure;

use App\ChildWatching\Shared\UserInterface\ChildWatchingExposedService;
use App\LetterProcessing\Shared\Domain\Child\Child;
use App\LetterProcessing\Shared\Domain\ChildWatchingGatewayInterface;
use App\LetterProcessing\Shared\Domain\ChildWatchingToLetterProcessingTranslator;
use App\LetterProcessing\Shared\Domain\SantaList;
use App\Shared\Domain\Exception\ExternalDependencyFailedException;
use App\Shared\Infrastructure\Symfony\Subscriber\ExceptionSubscriber;
use App\Shared\UserInterface\Api\ReadModel\ApiVersion;

readonly class PhpChildWatchingGateway implements ChildWatchingGatewayInterface
{
    public function __construct(
        private ChildWatchingExposedService $childWatchingExposedService,
    ) {
    }

    /**
     * @throws ExternalDependencyFailedException
     * @throws \JsonException
     */
    public function getSantaListForChild(Child $child): SantaList
    {
        try {
            $childRepresentation = $this->childWatchingExposedService->getChild($child->getId(), ApiVersion::fromInt(1));
        } catch (\Throwable $exception) {
            $externalDependencyFailedException = new ExternalDependencyFailedException(ExceptionSubscriber::getStatusCodeFor($exception));
            $externalDependencyFailedException->addMetadatas('externalResponseContent', $externalDependencyFailedException->getMessage());

            throw $externalDependencyFailedException;
        }

        /** @var array<string, mixed> $arrayChildRepresentation */
        $arrayChildRepresentation = json_decode(json: $childRepresentation, associative: true, flags: JSON_THROW_ON_ERROR);

        return (new ChildWatchingToLetterProcessingTranslator())->getSantaListForChildBasedOnItsActions(
            numberOfGoodActions: \intval($arrayChildRepresentation['numberOfGoodActions']),
            numberOfBadActions: \intval($arrayChildRepresentation['numberOfBadActions'])
        );
    }
}
