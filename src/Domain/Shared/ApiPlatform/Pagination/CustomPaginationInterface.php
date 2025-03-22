<?php

namespace App\Domain\Shared\ApiPlatform\Pagination;

interface CustomPaginationInterface
{
    public function setIterator(array $iterator): void;
}
