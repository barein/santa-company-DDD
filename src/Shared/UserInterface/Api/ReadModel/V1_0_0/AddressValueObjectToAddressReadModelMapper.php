<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api\ReadModel\V1_0_0;

use App\Shared\Domain\Address;
use App\Shared\UserInterface\Api\ReadModel\AddressValueObjectToAddressReadModelMapperInterface;
use App\Shared\UserInterface\Api\ReadModel\ApiVersion;

class AddressValueObjectToAddressReadModelMapper implements AddressValueObjectToAddressReadModelMapperInterface
{
    public function getVersion(): ApiVersion
    {
        return ApiVersion::fromInt(100);
    }

    public function map(Address $address): AddressReadModel
    {
        return new AddressReadModel(
            $address->getNumber(),
            $address->getStreet(),
            $address->getCity(),
            $address->getZipCode(),
            $address->getIsoCountryCode()->getValue(),
        );
    }
}
