<?php

namespace App\Domain\Shared\Criteria;

final class Criteria
{
    /**
     * @param iterable<Filter> $filters
     * @param iterable<Order>  $orders
     */
    public function __construct(
        public iterable $filters,
        public iterable $orders = [],
        public int $page = 1,
        public bool $fat = false
    ) {
    }
}
