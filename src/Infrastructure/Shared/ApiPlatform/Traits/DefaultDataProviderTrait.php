<?php

namespace App\Infrastructure\Shared\ApiPlatform\Traits;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;

trait DefaultDataProviderTrait
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|iterable|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->getCollection($context['filters'] ?? [], $uriVariables);
        }

        return $this->getItem($uriVariables['id']);
    }
}
