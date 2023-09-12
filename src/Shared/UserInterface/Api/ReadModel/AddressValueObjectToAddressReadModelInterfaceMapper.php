<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api\ReadModel;

use App\Shared\Domain\Address;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class AddressValueObjectToAddressReadModelInterfaceMapper
{
    /**
     * @param iterable<AddressValueObjectToAddressReadModelMapperInterface> $mappers
     */
    public function __construct(
        #[TaggedIterator(AddressValueObjectToAddressReadModelMapperInterface::class)]
        private iterable $mappers,
        private readonly ReadModelMapperFinder $readModelMapperFinder,
    ) {
    }

    public function map(Address $address, ApiVersion $version): AddressReadModelInterface
    {
        $mostRecentMapperForRequiredVersion = $this->readModelMapperFinder->findMostRecentMapperForVersion(
            sourceDomainObject: $address,
            destinationReadModelInterface: AddressReadModelInterface::class,
            version: $version,
            mappers: $this->mappers,
        );

        return $mostRecentMapperForRequiredVersion->map($address);
    }
}
