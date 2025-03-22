<?php

namespace App\Domain\Shared\Service;

interface ArrayToObjectHydratorServiceInterface
{
    public function hydrate(array $params, string $className);
}
