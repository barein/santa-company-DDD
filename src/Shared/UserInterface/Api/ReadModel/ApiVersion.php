<?php

declare(strict_types=1);

namespace App\Shared\UserInterface\Api\ReadModel;

use App\Shared\Domain\Exception\InvalidArgumentException;

final class ApiVersion
{
    public const VERSION_MIN = 100;

    private int $value;

    private function __construct(int $version)
    {
        $this->value = $version;
    }

    public static function fromInt(int $version): self
    {
        if ($version < self::VERSION_MIN) {
            throw new InvalidArgumentException(sprintf('Version %s is not supported.', $version));
        }

        return new self($version);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromString(string $version): self
    {
        $result = (bool) preg_match('#v(?<major>\d)\.(?<minor>\d)\.(?<patch>\d)#', $version, $matches);

        if ($result === false) {
            throw new InvalidArgumentException(sprintf(
                'API version could not be resolved from string %s, check that it matches the following regex: v\d\.\d\.\d',
                $version,
            ));
        }

        $versionAsInteger = \intval($matches['major'] . $matches['minor'] . $matches['patch']);

        return self::fromInt($versionAsInteger);
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        $numbersOfVersionsAsArray = preg_split('#\d#', \strval($this->value));

        if ($numbersOfVersionsAsArray === false) {
            throw new \InvalidArgumentException(sprintf(
                'An error happened while splitting numbers of %d',
                $this->value,
            ));
        }

        return join('.', $numbersOfVersionsAsArray);
    }
}
