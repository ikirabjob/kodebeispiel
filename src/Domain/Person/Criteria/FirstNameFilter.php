<?php

namespace App\Domain\Person\Criteria;

use App\Domain\Shared\Criteria\Filter;

final class FirstNameFilter extends Filter
{
    public function __construct(mixed $value)
    {
        parent::__construct('userName.firstName', self::ILIKE, $value.'%');
    }
}
