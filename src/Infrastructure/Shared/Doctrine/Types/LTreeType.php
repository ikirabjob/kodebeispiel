<?php

namespace App\Infrastructure\Shared\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Class LTreeType.
 */
class LTreeType extends Type
{
    public const TYPE_NAME = 'ltree';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return static::TYPE_NAME;
    }

    /**
     * Converts a value from its database representation to its PHP representation
     * of this type.
     *
     * @param mixed            $value    the value to convert
     * @param AbstractPlatform $platform the currently used database platform
     *
     * @return mixed the PHP representation of the value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if (is_scalar($value)) {
            $value = (string) $value;
        }

        return explode('.', $value);
    }

    /**
     * Converts a value from its PHP representation to its database representation
     * of this type.
     *
     * @param mixed            $value    the value to convert
     * @param AbstractPlatform $platform the currently used database platform
     *
     * @return mixed the database representation of the value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if (is_scalar($value)) {
            $value = (array) $value;
        }

        return implode('.', $value);
    }

    public function getName(): string
    {
        return self::TYPE_NAME;
    }
}
