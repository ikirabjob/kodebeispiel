<?php

namespace App\Domain\Dictionary\Criteria;

use App\Domain\Dictionary\QueryBuilder\NativeCondition;
use App\Domain\Dictionary\QueryBuilder\NativeFilter;
use App\Domain\Dictionary\QueryBuilder\NativeJoin;

class EmailFilter extends NativeFilter
{
    public function __construct(string $value)
    {
        parent::__construct(
            [
                new NativeCondition('c.contact_type', self::IN, [1, 2]),
                new NativeCondition("(c.content->>'email')::text", self::LIKE, '%'.$value.'%'),
            ],
            [
                new NativeJoin('contact', 'c', 'person_id'),
            ]
        );
    }
}
