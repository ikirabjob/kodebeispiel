<?php

namespace App\Domain\Dictionary\QueryBuilder;

final class NativeOrder
{
    public function __construct(
        public string $field,
        public string $direction = 'ASC'
    ) {
    }
}
