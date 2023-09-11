<?php

declare(strict_types=1);

namespace App\ChildWatching\GetChild\UserInterface\Api\ReadModel;

use App\ChildWatching\GetChild\Application\Query\GetChildResult;
use App\ChildWatching\Shared\UserInterface\Api\ReadModel\ChildReadModelInterface;
use App\Shared\UserInterface\Api\ReadModel\ApiVersion;
use App\Shared\UserInterface\Api\ReadModel\ReadModelMapperFinder;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class GetChildResultToChildReadModelInterfaceMapper
{
    /**
     * @param iterable<GetChildResultToChildReadModelMapperInterface> $mappers
     */
    public function __construct(
        #[TaggedIterator(GetChildResultToChildReadModelMapperInterface::class)]
        private iterable $mappers,
        private readonly ReadModelMapperFinder $readModelMapperFinder,
    ) {
    }

    public function map(GetChildResult $getChildResult, ApiVersion $apiVersion): ChildReadModelInterface
    {
        $mostRecentMapperForRequiredVersion = $this->readModelMapperFinder->findMostRecentMapperForVersion(
            sourceDomainObject: $getChildResult,
            destinationReadModelInterface: ChildReadModelInterface::class,
            version: $apiVersion,
            mappers: $this->mappers,
        );

        return $mostRecentMapperForRequiredVersion->map($getChildResult);
    }
}
