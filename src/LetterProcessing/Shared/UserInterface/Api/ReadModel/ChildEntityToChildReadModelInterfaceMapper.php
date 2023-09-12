<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\UserInterface\Api\ReadModel;

use App\LetterProcessing\Shared\Domain\Child\ChildReadInterface;
use App\Shared\UserInterface\Api\ReadModel\ApiVersion;
use App\Shared\UserInterface\Api\ReadModel\ReadModelMapperFinder;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ChildEntityToChildReadModelInterfaceMapper
{
    private ?ChildEntityToChildReadModelMapperInterface $matchedMapper = null;

    /**
     * @param iterable<ChildEntityToChildReadModelMapperInterface> $mappers
     */
    public function __construct(
        #[TaggedIterator(ChildEntityToChildReadModelMapperInterface::class)]
        private iterable $mappers,
        private readonly ReadModelMapperFinder $readModelMapperFinder,
    ) {
    }

    public function map(ChildReadInterface $childEntity, ApiVersion $version): ChildReadModelInterface
    {
        if ($this->matchedMapper === null) {
            $this->matchedMapper = $this->readModelMapperFinder->findMostRecentMapperForVersion(
                sourceDomainObject: $childEntity,
                destinationReadModelInterface: ChildReadModelInterface::class,
                version: $version,
                mappers: $this->mappers,
            );
        }

        return $this->matchedMapper->map($childEntity, $version);
    }
}
