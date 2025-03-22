<?php

namespace App\Domain\Dictionary\Repository;

use App\Domain\Dictionary\QueryBuilder\NativeCriteria;

interface ReviseStatusDictionaryRepositoryInterface
{
    public function getFilteredList(NativeCriteria $criteria, int $page, bool $flat): mixed;

    public function makeWhereCondition(iterable $condition): self;

    public function makeJoins(iterable $joins): self;
}
