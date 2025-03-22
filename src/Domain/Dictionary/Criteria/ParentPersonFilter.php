<?php

namespace App\Domain\Dictionary\Criteria;

use App\Domain\Dictionary\QueryBuilder\NativeCondition;
use App\Domain\Dictionary\QueryBuilder\NativeFilter;

class ParentPersonFilter extends NativeFilter
{
    public function __construct(string $value)
    {
        parent::__construct(
            [
                new NativeCondition('p.parent_person_id', self::EQUAL, $value),
            ]
        );
    }
}
