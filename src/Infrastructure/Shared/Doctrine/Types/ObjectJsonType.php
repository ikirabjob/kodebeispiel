<?php

namespace App\Infrastructure\Shared\Doctrine\Types;

use App\Domain\Shared\ValueObject\Stamp;
use App\Infrastructure\Shared\Service\ArrayToObjectHydratorService;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class ObjectJsonType extends Type
{
    public const TYPE_NAME = 'object_json';
    protected ?string $className = Stamp::class;

    /**
     * @throws Exception
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        //        if (!isset($column['className'])) {
        //            throw new Exception('Missed required field option className');
        //        }
        //
        //        if (!class_exists($column["className"])) {
        //            throw new Exception(sprintf('Class "%s" not registered in system', $column['className']));
        //        }
        //
        //        $this->className = $column['className'];

        return $platform->getJsonTypeDeclarationSQL($column);
    }

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if (empty($value)) {
            return null;
        }

        $value = json_decode($value, true);

        try {
            return (new ArrayToObjectHydratorService())->hydrate($value, $this->className);
        } catch (\ReflectionException|\InvalidArgumentException $e) {
            // throw ConversionException::conversionFailed($value, $this->getName(), $e);
            return null;
        }
    }

    /**
     * @return false|mixed|string|null
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof \JsonSerializable) {
            throw ConversionException::conversionFailedSerialization($value, $this->getName(), sprintf('Type "%s" not instance of JsonSerializable', $this->className));
        }

        return json_encode($value->jsonSerialize());
    }
}
