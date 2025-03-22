<?php

namespace App\Domain\Person\Criteria;

use App\Domain\Shared\Criteria\Filter;

class ParentPersonFilter extends Filter
{
    public function __construct(string $value)
    {
        parent::__construct('parentPerson', self::EQUAL, $value);
    }
}
