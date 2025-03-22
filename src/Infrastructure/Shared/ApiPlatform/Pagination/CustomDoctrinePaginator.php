<?php

namespace App\Infrastructure\Shared\ApiPlatform\Pagination;

use ApiPlatform\Doctrine\Orm\AbstractPaginator;
use ApiPlatform\State\Pagination\PaginatorInterface;
use App\Domain\Shared\ApiPlatform\Pagination\CustomPaginationInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

final class CustomDoctrinePaginator extends AbstractPaginator implements CustomPaginationInterface, PaginatorInterface
{
    private ?int $totalItems = null;

    public function __construct(DoctrinePaginator $paginator)
    {
        parent::__construct($paginator);
    }

    public function getLastPage(): float
    {
        if (0 >= $this->maxResults) {
            return 1.;
        }

        return ceil($this->getTotalItems() / $this->maxResults) ?: 1.;
    }

    public function getTotalItems(): float
    {
        return (float) ($this->totalItems ?? $this->totalItems = \count($this->paginator));
    }

    public function setIterator(array $iterator): void
    {
        $this->iterator = new \ArrayIterator($iterator);
    }
}
