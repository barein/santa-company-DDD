<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Ulid;

final class UlidType extends Type
{
    public const NAME = 'ulid';

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof Ulid) {
            return $value->__toString();
        }

        if (\is_string($value)) {
            return $value;
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', 'string', Ulid::class]
        );
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $fieldDeclaration['length'] = 26;
        $fieldDeclaration['nullable'] = false;

        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?AbstractUid
    {
        if ($value === null) {
            return null;
        }

        if (!\is_string($value)) {
            return null;
        }

        return Ulid::fromString($value);
    }

    public function getName(): string
    {
        return static::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
