<?php

namespace App\Application\Person\Query;

use App\Application\Shared\Query\QueryInterface;
use App\Domain\Shared\Criteria\Criteria;

final class FindPersonsQuery implements QueryInterface
{
    public function __construct(public Criteria $criteria)
    {
    }
}
