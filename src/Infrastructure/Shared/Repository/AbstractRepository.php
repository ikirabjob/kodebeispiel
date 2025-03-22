<?php

namespace App\Infrastructure\Shared\Repository;

use App\Domain\Person\Exception\LoginNotFoundException;
use App\Domain\Shared\ApiPlatform\Pagination\CustomPaginationInterface;
use App\Infrastructure\Shared\ApiPlatform\Pagination\CustomDoctrinePaginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public const ITEMS_PER_PAGE = 30;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->getClass());
    }

    abstract protected function getClass(): string;

    /**
     * @throws LoginNotFoundException
     * @throws NonUniqueResultException
     */
    protected function oneOrException(QueryBuilder $queryBuilder): mixed
    {
        $entity = $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $entity) {
            throw new LoginNotFoundException('Login not found');
        }

        return $entity;
    }

    protected function one(QueryBuilder $queryBuilder): mixed
    {
        try {
            return $queryBuilder
                ->getQuery()
                ->setMaxResults(1)
                ->getSingleResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return null;
        }
    }

    protected function fatList(QueryBuilder $queryBuilder): array|int|string|null
    {
        try {
            return $queryBuilder
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @throws QueryException
     */
    protected function paginatedList(QueryBuilder $queryBuilder, ?int $page = 1): CustomPaginationInterface
    {
        $firstResult = ($page - 1) * self::ITEMS_PER_PAGE;

        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE);

        $queryBuilder->addCriteria($criteria);

        $doctrinePaginator = new DoctrinePaginator($queryBuilder);

        return new CustomDoctrinePaginator($doctrinePaginator);
    }

    protected function getEm(): EntityManagerInterface
    {
        return $this->getEntityManager();
    }
}
