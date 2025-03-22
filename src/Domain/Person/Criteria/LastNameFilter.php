<?php

namespace App\Domain\Person\Criteria;

use App\Domain\Shared\Criteria\Filter;

final class LastNameFilter extends Filter
{
    public function __construct(mixed $value)
    {
        parent::__construct('userName.lastName', self::ILIKE, $value.'%');
    }
}
