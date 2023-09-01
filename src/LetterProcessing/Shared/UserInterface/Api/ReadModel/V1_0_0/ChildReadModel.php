<?php

declare(strict_types=1);

namespace App\LetterProcessing\Shared\UserInterface\Api\ReadModel\V1_0_0;

use App\LetterProcessing\Shared\UserInterface\Api\ReadModel\ChildReadModelInterface;
use App\Shared\UserInterface\Api\ReadModel\AddressReadModelInterface;

readonly class ChildReadModel implements ChildReadModelInterface
{
    /**
     * @param string[] $letters
     */
    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public AddressReadModelInterface $address,
        public array $letters,
    ) {
    }
}
