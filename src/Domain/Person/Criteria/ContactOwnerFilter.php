<?php

namespace App\Domain\Person\Criteria;

use App\Domain\Shared\Criteria\Filter;

class ContactOwnerFilter extends Filter
{
    public function __construct(string $value)
    {
        parent::__construct('person', self::EQUAL, $value);
    }
}
