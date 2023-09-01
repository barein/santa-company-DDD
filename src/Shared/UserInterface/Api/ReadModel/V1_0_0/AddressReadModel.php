<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api\ReadModel\V1_0_0;

use App\Shared\UserInterface\Api\ReadModel\AddressReadModelInterface;

readonly class AddressReadModel implements AddressReadModelInterface
{
    public function __construct(
        public int $number,
        public string $street,
        public string $city,
        public int $zipCode,
        public string $isoCountryCode,
    ) {
    }
}
