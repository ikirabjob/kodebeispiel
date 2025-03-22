<?php

namespace App\Domain\Dictionary\QueryBuilder;

class NativeJoin
{
    public function __construct(
        public string $table,
        public string $alias,
        public string $on
    ) {
    }
}
