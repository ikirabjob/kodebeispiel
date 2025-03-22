<?php

namespace App\Infrastructure\Shared\ApiPlatform\Filter;

use ApiPlatform\Api\FilterInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

abstract class AbstractFilter implements FilterInterface
{
    protected ?array $properties;
    protected $nameConverter;

    public function __construct(array $properties = null)
    {
        $this->properties = $properties;
    }

    protected function getProperties(): ?array
    {
        return $this->properties;
    }

    protected function denormalizePropertyName($property): string
    {
        if (!$this->nameConverter instanceof NameConverterInterface) {
            return $property;
        }

        return implode('.', array_map([$this->nameConverter, 'denormalize'], explode('.', (string) $property)));
    }

    protected function normalizePropertyName($property): string
    {
        if (!$this->nameConverter instanceof NameConverterInterface) {
            return $property;
        }

        return implode('.', array_map([$this->nameConverter, 'normalize'], explode('.', (string) $property)));
    }
}
