<?php

declare(strict_types=1);

namespace App\Shared\Application;

use App\Shared\Domain\Exception\InvalidArgumentException;

final class ApiVersion
{
    public const VERSION_MIN = 1;
    public const VERSION_MAX = 1;

    private int $version;

    private function __construct(int $version)
    {
        $this->version = $version;
    }

    public static function fromInt(int $version): self
    {
        if ($version < self::VERSION_MIN || $version > self::VERSION_MAX) {
            throw new InvalidArgumentException(sprintf('Version %s is not supported.', $version));
        }

        return new self($version);
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
