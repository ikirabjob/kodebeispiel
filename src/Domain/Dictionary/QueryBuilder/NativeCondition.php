<?php

namespace App\Domain\Dictionary\QueryBuilder;

final class NativeCondition
{
    public function __construct(
        public string $field,
        public string $operator,
        public mixed $value
    ) {
    }
}
