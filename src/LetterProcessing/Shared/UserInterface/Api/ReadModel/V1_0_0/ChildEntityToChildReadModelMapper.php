<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\UserInterface\Api\ReadModel\V1_0_0;

use App\LetterProcessing\Shared\Domain\Child\ChildReadInterface;
use App\LetterProcessing\Shared\Domain\Letter\LetterReadInterface;
use App\LetterProcessing\Shared\UserInterface\Api\ReadModel\ChildEntityToChildReadModelMapperInterface;
use App\Shared\UserInterface\Api\ReadModel\AddressValueObjectToAddressReadModelInterfaceMapper;
use App\Shared\UserInterface\Api\ReadModel\ApiVersion;

readonly class ChildEntityToChildReadModelMapper implements ChildEntityToChildReadModelMapperInterface
{
    public function __construct(
        private AddressValueObjectToAddressReadModelInterfaceMapper $addressValueObjectToAddressReadModelInterfaceMapper,
    ) {
    }

    public function getVersion(): ApiVersion
    {
        return ApiVersion::fromInt(100);
    }

    public function map(ChildReadInterface $childEntity, ApiVersion $requiredSubResourcesVersion): ChildReadModel
    {
        $letters = array_map(
            fn (LetterReadInterface $letter) => (string) $letter->getId(),
            $childEntity->getLetters()
        );

        $addressReadModel = $this->addressValueObjectToAddressReadModelInterfaceMapper->map(
            $childEntity->getAddress(),
            $requiredSubResourcesVersion,
        );

        return new ChildReadModel(
            (string) $childEntity->getId(),
            $childEntity->getFirstName(),
            $childEntity->getLastName(),
            $addressReadModel,
            $letters
        );
    }
}
