<?php

namespace App\Domain\Shared\Criteria;

class GroupFilter
{
    public const OR = 'or';
    public const AND = 'and';

    public function __construct(
        public iterable $filters,
        public string $condition
    ) {
    }
}
