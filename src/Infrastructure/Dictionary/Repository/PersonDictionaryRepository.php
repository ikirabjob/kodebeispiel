<?php

namespace App\Infrastructure\Dictionary\Repository;

use App\Domain\Dictionary\QueryBuilder\NativeCriteria;
use App\Domain\Dictionary\QueryBuilder\NativePaginator;
use App\Domain\Dictionary\Repository\PersonDictionaryRepositoryInterface;
use App\Domain\Person\Model\Person;
use App\Infrastructure\Shared\Repository\AbstractRepository;
use App\Infrastructure\Shared\Repository\BuildNativeQueryTrait;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class PersonDictionaryRepository extends AbstractRepository implements PersonDictionaryRepositoryInterface
{
    use BuildNativeQueryTrait;

    protected function getClass(): string
    {
        return Person::class;
    }

    protected function getParentAlias(): string
    {
        return 'p';
    }

    public function makeWhereCondition(iterable $condition): self
    {
        foreach ($condition as $value) {
            $this->addCondition(
                strtr('and {field} {operator} {value}', [
                    '{field}' => $value->field,
                    '{operator}' => $value->operator,
                    '{value}' => $this->escape($value->value),
                ])
            );
        }

        return $this;
    }

    public function makeJoins(iterable $joins): self
    {
        foreach ($joins as $join) {
            $this->addJoin(
                strtr(
                    'left join {table} {alias} on {alias}.{on} = {parentAlias}.{on}',
                    [
                        '{table}' => $join->table,
                        '{alias}' => $join->alias,
                        '{on}' => $join->on,
                        '{parentAlias}' => $this->getParentAlias(),
                    ]
                )
            );
        }

        return $this;
    }

    public function makeOrders(iterable $orders): static
    {
        foreach ($orders as $order) {
            $this->addOrder(
                strtr(
                    '{field} {direction}',
                    [
                        '{field}' => $order->field,
                        '{direction}' => $order->direction,
                    ]
                )
            );
        }

        return $this;
    }

    public function getFilteredList(
        NativeCriteria $criteria,
        int $page,
        bool $flat
    ): mixed {
        $builder = new ResultSetMappingBuilder($this->getEntityManager());
        $builder->addRootEntityFromClassMetadata(Person::class, $this->getParentAlias());
        $select = $builder->generateSelectClause([$this->getParentAlias() => $this->getParentAlias()]);

        $query = "select {$select} FROM person {$this->getParentAlias()}";

        foreach ($criteria->nativeFilter as $criterion) {
            if ($criterion->joins) {
                $this->makeJoins($criterion->joins);
            }
            if ($criterion->conditions) {
                $this->makeWhereCondition($criterion->conditions);
            }
            if ($criterion->orders) {
                $this->makeOrders($criterion->orders);
            }
        }

        $personQuery = $this->getEntityManager()
            ->createNativeQuery($this->buildQuery($query), $builder);

        return false === $flat ? $this->paginated($personQuery, $page) : $personQuery->getResult();
    }

    public function paginated(NativeQuery $queryBuilder, ?int $page = 1): NativePaginator
    {
        $firstResult = ($page - 1) * self::ITEMS_PER_PAGE;

        return (new NativePaginator($queryBuilder))
            ->setMaxResult(self::ITEMS_PER_PAGE)->setOffset($firstResult);
    }

    private function escape($value): string
    {
        if (is_numeric($value) || is_bool($value)) {
            return $value;
        }

        if (is_array($value)) {
            return sprintf('(%s)', implode(',', $value));
        }

        if (is_string($value)) {
            return "'".str_replace("'", "''", $value)."'";
        }

        return $value;
    }
}
