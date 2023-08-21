<?php

declare(strict_types=1);

namespace App\Shared\Application\ReadModel\Address\V1;

readonly class AddressReadModel
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
