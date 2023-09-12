<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api\ReadModel;

interface VersionAwareInterface
{
    public function getVersion(): ApiVersion;
}
