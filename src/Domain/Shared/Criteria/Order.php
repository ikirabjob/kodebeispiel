<?php

namespace App\Domain\Shared\Criteria;

class Order
{
    public function __construct(
        public string $field,
        public string $direction = 'ASC'
    ) {
    }
}
