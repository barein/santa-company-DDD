<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api\ReadModel;

class ReadModelMapperFinder
{
    /**
     * @template T of VersionAwareInterface
     *
     * @param iterable<T> $mappers
     *
     * @throws NoReadModelMapperFound
     *
     * @return T
     */
    public function findMostRecentMapperForVersion(
        object $sourceDomainObject,
        string $destinationReadModelInterface,
        ApiVersion $version,
        iterable $mappers
    ): mixed {
        $mappers = [...$mappers];

        usort(
            $mappers,
            function (VersionAwareInterface $mapperA, VersionAwareInterface $mapperB) {
                return $mapperA->getVersion() <=> $mapperB->getVersion();
            }
        );

        $mostRecentMapperForRequiredVersion = null;
        foreach ($mappers as $mapper) {
            if ($mapper->getVersion()->getValue() <= $version->getValue()) {
                $mostRecentMapperForRequiredVersion = $mapper;

                continue;
            }

            break;
        }

        if ($mostRecentMapperForRequiredVersion === null) {
            throw new NoReadModelMapperFound(
                sourceClass: $sourceDomainObject::class,
                destinationReadModelInterface: $destinationReadModelInterface,
                version: $version,
            );
        }

        return $mostRecentMapperForRequiredVersion;
    }
}
