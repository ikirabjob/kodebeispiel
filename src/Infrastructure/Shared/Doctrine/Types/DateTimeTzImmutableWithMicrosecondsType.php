<?php

namespace App\Infrastructure\Shared\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeTzType;

class DateTimeTzImmutableWithMicrosecondsType extends DateTimeTzType
{
    private const TYPENAME = 'datetimetz_immutable_with_microseconds';

    public function getName(): string
    {
        return self::TYPENAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if (isset($column['version']) && true === $column['version']) {
            return 'TIMESTAMP';
        }
        if ($platform instanceof PostgreSQLPlatform) {
            return 'TIMESTAMP(6) WITH TIME ZONE';
        }

        return 'DATETIME(6)';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if (null === $value || $value instanceof \DateTimeInterface) {
            return $value;
        }
        if (str_contains($value, '.')) {
            return \DateTimeImmutable::createFromFormat('Y-m-d H:i:s.uO', $value);
        }

        return \DateTimeImmutable::createFromFormat('Y-m-d H:i:sO', $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (null === $value) {
            return null;
        }
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s.uO');
        }
        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTime']);
    }
}
