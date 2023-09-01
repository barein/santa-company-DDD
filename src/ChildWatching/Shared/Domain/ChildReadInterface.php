<?php

declare(strict_types=1);

namespace App\ChildWatching\Shared\Domain;

use App\Shared\Domain\Address;
use Symfony\Component\Uid\Ulid;

interface ChildReadInterface
{
    public function getId(): Ulid;

    public function getAddress(): Address;
}
