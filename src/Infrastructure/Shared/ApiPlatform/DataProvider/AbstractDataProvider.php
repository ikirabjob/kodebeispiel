<?php

namespace App\Infrastructure\Shared\ApiPlatform\DataProvider;

use ApiPlatform\State\ProviderInterface;
use App\Infrastructure\Shared\ApiPlatform\Traits\DefaultDataProviderTrait;

abstract class AbstractDataProvider implements ProviderInterface
{
    use DefaultDataProviderTrait;

    abstract public function getItem(int|string $id);

    abstract public function getCollection(array $contextFilters = [], array $uriVariables = []);

    abstract public function transform($data);
}
