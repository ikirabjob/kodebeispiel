<?php

namespace App\Infrastructure\Shared\Repository;

trait BuildNativeQueryTrait
{
    private array $joins = [];
    private array $conditions = [];
    private array $orders = [];

    protected function addJoin(string $joinQuery): void
    {
        $this->joins[] = $joinQuery;
    }

    protected function addCondition(string $conditionQuery): void
    {
        $this->conditions[] = $conditionQuery;
    }

    protected function addOrder(string $orderQuery): void
    {
        $this->orders[] = $orderQuery;
    }

    protected function buildQuery(string $select): string
    {
        if ($this->joins) {
            $select .= ' '.implode(' ', $this->joins);
        }

        if ($this->conditions) {
            $select .= ' where ';
            $select .= ' '.preg_replace('/^(OR|AND)/i', '',
                implode(' ', $this->conditions));
        }

        if ($this->orders) {
            $select .= ' order by ';
            $select .= ' '.implode(' ', $this->orders);
        }

        return $select;
    }
}
