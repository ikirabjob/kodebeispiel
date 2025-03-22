<?php

namespace App\Domain\Dictionary\QueryBuilder;

class NativeFilter
{
    public const EQUAL = '=';
    public const LIKE = 'LIKE';
    public const ILIKE = 'ILIKE';
    public const IN = 'IN';

    /**
     * @param iterable<NativeCondition> $conditions
     * @param iterable<NativeJoin>      $joins
     * @param iterable<NativeOrder>     $orders
     */
    public function __construct(
        public iterable $conditions,
        public iterable $joins = [],
        public iterable $orders = [],
    ) {
    }
}
