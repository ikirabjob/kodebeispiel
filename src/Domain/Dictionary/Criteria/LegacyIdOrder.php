<?php

namespace App\Domain\Dictionary\Criteria;

use App\Domain\Dictionary\QueryBuilder\NativeFilter;
use App\Domain\Dictionary\QueryBuilder\NativeOrder;

class LegacyIdOrder extends NativeFilter
{
    public function __construct(string $field, string $direction = 'ASC')
    {
        parent::__construct([], [], [new NativeOrder($field, $direction)]);
    }
}
