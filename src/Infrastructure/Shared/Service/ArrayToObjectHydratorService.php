<?php

namespace App\Infrastructure\Shared\Service;

use App\Domain\Document\ValueObject\DocumentInterface;
use App\Domain\Person\ValueObject\Email;
use App\Domain\Person\ValueObject\Phone;
use App\Domain\Shared\Service\ArrayToObjectHydratorServiceInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

final class ArrayToObjectHydratorService implements ArrayToObjectHydratorServiceInterface
{
    /**
     * @throws \ReflectionException
     */
    public function hydrate(array $params, string $className)
    {
        $class = new \ReflectionClass($className);

        if ($class->getConstructor()->getNumberOfRequiredParameters() > count($params)) {
            throw new \InvalidArgumentException(sprintf('Wrong parameters count. Should be %d params. (%s)', $class->getConstructor()->getNumberOfRequiredParameters(), implode(', ', array_map(fn (\ReflectionParameter $param) => $param->getName(), $class->getConstructor()->getParameters()))));
        }

        $normalizedParams = array_combine(
            array_map(fn ($el) => $this->normalizeToCamelCase($el), array_keys($params)),
            array_values($params),
        );

        $this->typeParams($class->getConstructor()->getParameters(), $normalizedParams);

        try {
            /* @var DocumentInterface $instance */
            return $class->newInstanceArgs($normalizedParams);
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    private function normalizeToCamelCase(string $variableName): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $variableName))));
    }

    private function typeParams(array $classConstructorParams, array &$normalizedParams): void
    {
        /** @var \ReflectionParameter $classConstructorParam */
        foreach ($classConstructorParams as $classConstructorParam) {
            if (!$classConstructorParam->hasType()) {
                continue;
            }

            $types = [];

            $paramName = $classConstructorParam->getName();

            if (!isset($normalizedParams[$paramName])) {
                continue;
            }

            if ($classConstructorParam->getType() instanceof \ReflectionUnionType) {
                $types = array_map(
                    fn (\ReflectionNamedType $type) => $type->getName(),
                    $classConstructorParam->getType()->getTypes()
                );
            } else {
                $types[] = $classConstructorParam->getType()->getName();
            }

            if (in_array(gettype($normalizedParams[$paramName]), $types)) {
                continue;
            }

            $normalizedParams[$paramName] = $this->castToPossibleType($normalizedParams[$paramName], $types);
        }
    }

    /**
     * @return mixed|UuidV4
     */
    private function castToPossibleType(mixed $param, array $types): mixed
    {
        if (in_array(Uuid::class, $types)) {
            return UuidV4::fromString($param);
        } elseif (in_array(Phone::class, $types)) {
            return Phone::fromString($param);
        } elseif (in_array(Email::class, $types)) {
            return Email::fromString($param);
        }

        return $param;
    }
}
