<?php

namespace App\Application\Person\Query;

use App\Application\Shared\Query\QueryInterface;

final class FindCountryQuery implements QueryInterface
{
    public function __construct(public string $id)
    {
    }
}
