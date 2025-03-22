<?php

namespace App\Domain\Dictionary\Criteria;

use App\Domain\Dictionary\QueryBuilder\NativeCondition;
use App\Domain\Dictionary\QueryBuilder\NativeFilter;
use App\Domain\Dictionary\QueryBuilder\NativeJoin;

class MembershipFeeStatusFilter extends NativeFilter
{
    public function __construct(string $value)
    {
        parent::__construct(
            [
                new NativeCondition('mb_t.status', self::EQUAL, $value),
            ],
            [
                new NativeJoin('membership_fee', 'mb_t', 'person_id'),
            ]
        );
    }
}
