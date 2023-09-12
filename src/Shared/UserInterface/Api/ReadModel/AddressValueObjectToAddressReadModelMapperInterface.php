<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api\ReadModel;

use App\Shared\Domain\Address;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface AddressValueObjectToAddressReadModelMapperInterface extends VersionAwareInterface
{
    public function map(Address $address): AddressReadModelInterface;
}
