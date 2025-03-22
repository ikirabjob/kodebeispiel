<?php

namespace App\Infrastructure\Shared\ApiPlatform\Filter;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;

class SearchFilter extends AbstractFilter implements SearchFilterInterface
{
    public function getDescription(string $resourceClass): array
    {
        $description = [];

        $properties = $this->getProperties();

        if (null === $properties) {
            $properties = array_fill_keys([], null);
        }

        foreach ($properties as $property => $strategy) {
            $propertyName = $this->normalizePropertyName($property);

            $strategy = $this->getProperties()[$property] ?? self::STRATEGY_EXACT;
            $filterParameterNames = [$propertyName];

            //            if (self::STRATEGY_EXACT === $strategy) {
            //                $filterParameterNames[] = $propertyName . '[]';
            //            }

            foreach ($filterParameterNames as $filterParameterName) {
                $description[$filterParameterName] = [
                    'property' => $propertyName,
                    'type' => 'string',
                    'required' => false,
                    'strategy' => $strategy,
                    'is_collection' => str_ends_with((string) $filterParameterName, '[]'),
                ];
            }
        }

        return $description;
    }
}
