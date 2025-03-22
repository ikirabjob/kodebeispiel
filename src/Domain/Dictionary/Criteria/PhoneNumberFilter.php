<?php

namespace App\Domain\Dictionary\Criteria;

use App\Domain\Dictionary\QueryBuilder\NativeCondition;
use App\Domain\Dictionary\QueryBuilder\NativeFilter;
use App\Domain\Dictionary\QueryBuilder\NativeJoin;

class PhoneNumberFilter extends NativeFilter
{
    public function __construct(string $value)
    {
        parent::__construct(
            [
                new NativeCondition('c.contact_type', self::EQUAL, 3),
                new NativeCondition("(c.content->>'phoneNumber')::text", self::LIKE, '%'.$value.'%'),
            ],
            [
                new NativeJoin('contact', 'c', 'person_id'),
            ]
        );
    }
}
