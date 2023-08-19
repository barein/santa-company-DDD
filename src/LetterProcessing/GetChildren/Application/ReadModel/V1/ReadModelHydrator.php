<?php

declare(strict_types=1);

namespace App\LetterProcessing\GetChildren\Application\ReadModel\V1;

use App\LetterProcessing\GetChildren\Application\ReadModel\GetChildrenReadModelDto;
use App\LetterProcessing\Shared\Application\ReadModel\Child\V1\ChildReadModel;
use App\LetterProcessing\Shared\Domain\Letter\Letter;
use App\Shared\Application\ReadModel\Address\V1\AddressReadModel;
use App\Shared\Application\ReadModelHydratorInterface;

class ReadModelHydrator implements ReadModelHydratorInterface
{
    /**
     * @param GetChildrenReadModelDto $dto
     */
    public function hydrate(object $dto): object
    {
        $children = [];

        foreach ($dto->children as $child) {
            $address = new AddressReadModel(
                $child->getAddress()->getNumber(),
                $child->getAddress()->getStreet(),
                $child->getAddress()->getCity(),
                $child->getAddress()->getZipCode(),
                $child->getAddress()->getIsoCountryCode()->getValue(),
            );

            /** @var string[] $letters */
            $letters = $child->getLetters()
                ->map(fn (Letter $letter) => (string) $letter->getId())
                ->toArray();

            $children[] = new ChildReadModel(
                (string) $child->getId(),
                $child->getFirstName(),
                $child->getLastName(),
                $address,
                $letters,
            );
        }

        return new ChildrenReadModel($children);
    }
}
