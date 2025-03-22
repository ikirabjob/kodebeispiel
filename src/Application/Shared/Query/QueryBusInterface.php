<?php

namespace App\Application\Shared\Query;

interface QueryBusInterface
{
    public function ask(QueryInterface $query): mixed;
}
