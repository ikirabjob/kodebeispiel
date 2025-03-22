<?php

namespace App\Domain\Person\Criteria;

use App\Domain\Shared\Criteria\Filter;

final class ContactTypeFilter extends Filter
{
    public function __construct(mixed $value)
    {
        parent::__construct('contactType', self::EQUAL, $value);
    }
}
