<?php

namespace App\Domain\Dictionary\Criteria;

use App\Domain\Dictionary\QueryBuilder\NativeCondition;
use App\Domain\Dictionary\QueryBuilder\NativeFilter;
use App\Domain\Dictionary\QueryBuilder\NativeJoin;

class ContractNumberFilter extends NativeFilter
{
    public function __construct(string $value)
    {
        parent::__construct(
            [
                new NativeCondition('c_t.contract_number', self::EQUAL, $value),
            ],
            [
                new NativeJoin('contract', 'c_t', 'person_id'),
            ]
        );
    }
}
