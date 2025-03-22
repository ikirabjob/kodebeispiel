<?php

namespace App\Infrastructure\Shared\Repository;

use App\Domain\Shared\Criteria\Criteria;
use App\Domain\Shared\Criteria\Filter;
use App\Domain\Shared\Criteria\GroupFilter;
use Doctrine\ORM\QueryBuilder;

trait SearchByCriteriaTrait
{
    private int $incrementedAssociation = 1;
    private int $incrementedName = 1;

    public function generateJoinAlias(string $association): string
    {
        return sprintf('%s_a%d', $association, $this->incrementedAssociation++);
    }

    public function generateParameterName(string $name): string
    {
        return sprintf('%s_p%d', str_replace('.', '_', $name), $this->incrementedName++);
    }

    protected function applyJoins(QueryBuilder $qb, array $fields, $parentAlias): string
    {
        foreach ($fields as $joinField) {
            $joinAlias = $this->generateJoinAlias($joinField);
            $qb->innerJoin(sprintf('%s.%s', $parentAlias, $joinField), $joinAlias);
            $parentAlias = $joinAlias;
        }

        return $parentAlias;
    }

    protected function hasField(string $fieldName): bool
    {
        $cmf = $this->getEntityManager()->getMetadataFactory();
        $class = $cmf->getMetadataFor($this->getClass());

        return $class->hasField($fieldName);
    }

    public function generateConditions(Filter $filter, QueryBuilder $qb, string $criteriaAlias, int $iterator): void
    {
        if (str_contains($filter->field, '.') && !$this->hasField($filter->field)) {
            $fields = explode('.', $filter->field);
            $criteriaField = array_pop($fields);
            $alias = $this->applyJoins($qb, $fields, $criteriaAlias);

            $qb
                ->andWhere(sprintf('%s.%s %s :value_%d', $alias, $criteriaField, $filter->operator, $iterator));
        } else {
            $qb
                ->andWhere(sprintf('%s.%s %s :value_%d',
                    $criteriaAlias, $filter->field, $filter->operator, $iterator));
        }

        $qb
            ->setParameter(sprintf('value_%d', $iterator), $filter->value);
    }

    private function generateInCondition(Filter $filter, QueryBuilder $qb, string $criteriaAlias, int $iterator): void
    {
        if (str_contains($filter->field, '.') && !$this->hasField($filter->field)) {
            $fields = explode('.', $filter->field);
            $criteriaField = array_pop($fields);
            $alias = $this->applyJoins($qb, $fields, $criteriaAlias);

            $qb
                ->andWhere(
                    $qb->expr()->in(
                        sprintf('%s.%s', $alias, $criteriaField),
                        $filter->value
                    )
                );
        } else {
            $qb
                ->andWhere(
                    $qb->expr()->in(
                        sprintf('%s.%s', $criteriaAlias, $filter->field),
                        $filter->value
                    )
                );
        }
    }

    private function generateILikeCondition(Filter $filter, QueryBuilder $qb, string $criteriaAlias, int $iterator): void
    {
        if (str_contains($filter->field, '.') && !$this->hasField($filter->field)) {
            $fields = explode('.', $filter->field);
            $criteriaField = array_pop($fields);
            $alias = $this->applyJoins($qb, $fields, $criteriaAlias);

            $qb
                ->andWhere(sprintf('%s(%s.%s, :value_%d) = true',
                    $filter->operator, $alias, $criteriaField, $iterator));
        } else {
            $qb
                ->andWhere(sprintf('%s(%s.%s, :value_%d) = true',
                    $filter->operator, $criteriaAlias, $filter->field, $iterator));
        }

        $qb
            ->setParameter(sprintf('value_%d', $iterator), $filter->value);
    }

    private function generateLikeCondition(Filter $filter, QueryBuilder $qb, string $criteriaAlias, int $iterator): void
    {
        if (str_contains($filter->field, '.') && !$this->hasField($filter->field)) {
            $fields = explode('.', $filter->field);
            $criteriaField = array_pop($fields);
            $alias = $this->applyJoins($qb, $fields, $criteriaAlias);

            $qb
                ->andWhere(sprintf('%s.%s %s :value_%d',
                    $alias, $criteriaField, $filter->operator, $iterator));
        } else {
            $qb
                ->andWhere(sprintf('%s.%s %s :value_%d',
                    $criteriaAlias, $filter->field, $filter->operator, $iterator));
        }

        $qb
            ->setParameter(sprintf('value_%d', $iterator), '%'.$filter->value.'%');
    }

    public function generateConditionGroup(GroupFilter $filter, QueryBuilder $qb, string $criteriaAlias, int $iterator): void
    {
        $items = [];

        /** @var Filter $filterRow */
        foreach ($filter->filters as $i => $filterRow) {
            if (str_contains($filterRow->field, '.') && !$this->hasField($filterRow->field)) {
                $fields = explode('.', $filterRow->field);
                $criteriaField = array_pop($fields);
                $alias = $this->applyJoins($qb, $fields, $criteriaAlias);

                $items[] = sprintf('%s.%s %s :value_%d_%d', $alias, $criteriaField, $filterRow->operator, $iterator, $i);

                $qb->setParameter(sprintf(':value_%d_%d', $iterator, $i), $filterRow->value);
            } else {
                $items[] = match ($filterRow->operator) {
                    Filter::ILIKE === $filterRow->operator, Filter::LIKE === $filterRow->operator => $qb->expr()->like($filterRow->field, $filterRow->value),
                    Filter::IN === $filterRow->operator => $qb->expr()->in($filterRow->field, $filterRow->value),
                    default => $qb->expr()->eq($filterRow->field, $filterRow->value),
                };
            }
        }

        if (GroupFilter::OR === $filter->condition) {
            $qb->andWhere($qb->expr()->orX(...$items));
        } elseif (GroupFilter::AND === $filter->condition) {
            $qb->andWhere($qb->expr()->andX(...$items));
        }
    }

    public function generateSqlQuery(Filter $filter, QueryBuilder $qb, string $criteriaAlias, int $iterator): void
    {
        if (
            Filter::EQUAL === $filter->operator
            || Filter::GREATER_THAN === $filter->operator
            || Filter::LETTER_THAN === $filter->operator
        ) {
            $this->generateConditions($filter, $qb, $criteriaAlias, $iterator);
        }

        if (Filter::LIKE === $filter->operator) {
            $this->generateLikeCondition($filter, $qb, $criteriaAlias, $iterator);
        }

        if (Filter::ILIKE === $filter->operator) {
            $this->generateILikeCondition($filter, $qb, $criteriaAlias, $iterator);
        }

        if (Filter::IN === $filter->operator) {
            $this->generateInCondition($filter, $qb, $criteriaAlias, $iterator);
        }
    }

    public function createQueryBuilderByCriteria(Criteria $criteria): QueryBuilder
    {
        $criteriaAlias = 'c_a';
        $qb = $this->createQueryBuilder($criteriaAlias);

        foreach ($criteria->filters as $i => $filter) {
            if ($filter instanceof GroupFilter) {
                $this->generateConditionGroup($filter, $qb, $criteriaAlias, $i);
            } elseif ($filter instanceof Filter) {
                $this->generateSqlQuery($filter, $qb, $criteriaAlias, $i);
            }
        }

        foreach ($criteria->orders as $order) {
            if (str_contains($order->field, '.')) {
                $fields = explode('.', $order->field);
                $criteriaField = array_pop($fields);
                $alias = $this->applyJoins($qb, $fields, $criteriaAlias);

                $qb
                    ->addOrderBy(sprintf('%s.%s', $alias, $criteriaField), $order->direction);
            } else {
                $qb
                    ->addOrderBy(sprintf('%s.%s', $criteriaAlias, $order->field), $order->direction);
            }
        }

        return $qb;
    }

    public function searchByCriteria(Criteria $criteria): iterable
    {
        // dd($this->createQueryBuilderByCriteria($criteria)->getQuery()->getSQL(), $this->createQueryBuilderByCriteria($criteria)->getQuery()->getParameters());die;
        return false !== $criteria->fat ?
            $this->fatList($this->createQueryBuilderByCriteria($criteria)) :
            $this->paginatedList($this->createQueryBuilderByCriteria($criteria), $criteria->page);
    }
}
