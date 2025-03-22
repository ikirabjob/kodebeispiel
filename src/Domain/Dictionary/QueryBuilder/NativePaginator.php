<?php

namespace App\Domain\Dictionary\QueryBuilder;

use ApiPlatform\State\Pagination\PaginatorInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\NativeQuery;

class NativePaginator implements PaginatorInterface, \IteratorAggregate
{
    protected NativeQuery $query;

    protected float $count;

    protected float $maxResult;

    protected float $offset;

    protected array|\Traversable $iterator;

    public function __construct(NativeQuery $query)
    {
        $this->query = $query;
    }

    /**
     * Returns the total number of rows in the result set.
     *
     * @throws Exception|\Exception
     */
    public function count(): int
    {
        return $this->countItems();
    }

    /**
     * @throws Exception
     */
    public function getLastPage(): float
    {
        if (0 >= $this->maxResult) {
            return 1.;
        }

        return ceil($this->getTotalItems() / $this->maxResult) ?: 1.;
    }

    /**
     * @throws Exception
     */
    public function getTotalItems(): float
    {
        return $this->count();
    }

    public function getCurrentPage(): float
    {
        if (0 >= $this->maxResult) {
            return 1.;
        }

        return floor($this->offset / $this->maxResult) + 1.;
    }

    public function getItemsPerPage(): float
    {
        return $this->maxResult;
    }

    public function setMaxResult(float $maxResult): static
    {
        $this->maxResult = $maxResult;

        return $this;
    }

    public function setOffset(float $offset): static
    {
        $this->offset = $offset;

        return $this;
    }

    public function getIterator(): \Traversable
    {
        return $this->iterator ?? $this->iterator = new \ArrayIterator($this->getItems());
    }

    public function setIterator(array $iterator): void
    {
        $this->iterator = new \ArrayIterator($iterator);
    }

    public function getItems()
    {
        $cloneQuery = clone $this->query;
        $cloneQuery->setParameters($this->query->getParameters());
        foreach ($this->query->getHints() as $name => $value) {
            $cloneQuery->setHint($name, $value);
        }
        // add on limit and offset
        $sql = $cloneQuery->getSQL();
        $sql .= " LIMIT {$this->maxResult} OFFSET {$this->offset}";
        $cloneQuery->setSQL($sql);

        return $cloneQuery->getResult();
    }

    /**
     * @throws Exception
     */
    public function countItems()
    {
        $sql = 'select count(*) from ('.$this->query->getSql().') as count';
        $db = $this->query->getEntityManager()->getConnection();

        return (int) $db->fetchFirstColumn($sql, $this->query->getParameters()->toArray())[0];
    }
}
