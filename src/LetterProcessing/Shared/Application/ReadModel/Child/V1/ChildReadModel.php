<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\Application\ReadModel\Child\V1;

use App\Shared\Application\ReadModel\Address\V1\AddressReadModel;

readonly class ChildReadModel
{
    /**
     * @param string[] $letters
     */
    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public AddressReadModel $address,
        public array $letters,
    ) {
    }
}
