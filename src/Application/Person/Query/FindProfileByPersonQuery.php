<?php

namespace App\Application\Person\Query;

use App\Application\Shared\Query\QueryInterface;

class FindProfileByPersonQuery implements QueryInterface
{
    public function __construct(
        public string $personId
    ) {
    }
}
