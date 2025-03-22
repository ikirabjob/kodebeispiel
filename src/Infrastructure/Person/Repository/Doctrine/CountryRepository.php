<?php

namespace App\Infrastructure\Person\Repository\Doctrine;

use App\Domain\Person\Model\Country;
use App\Domain\Person\Repository\CountryRepositoryInterface;
use App\Domain\Shared\Criteria\Criteria;
use App\Domain\Shared\Criteria\Filter;
use App\Infrastructure\Shared\Repository\AbstractRepository;
use App\Infrastructure\Shared\Repository\SearchByCriteriaTrait;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\Uid\Uuid;

class CountryRepository extends AbstractRepository implements CountryRepositoryInterface
{
    use SearchByCriteriaTrait;

    public function search(Uuid $id): ?Country
    {
        return $this->find($id);
    }

    public function searchByLegacyId(int $legacyId): ?Country
    {
        $builder = new ResultSetMappingBuilder($this->getEntityManager());
        $builder->addRootEntityFromClassMetadata(Country::class, 'c');
        $select = $builder->generateSelectClause(['c' => 'c']);

        $personQuery = $this->getEntityManager()
            ->createNativeQuery("select {$select} from country c where (c.legacy_marker->>'country_id')::int = :legacyId", $builder)
            ->setParameters([
                ':legacyId' => $legacyId,
            ]);

        try {
            /* @var Country $country */
            return $personQuery->getSingleResult(AbstractQuery::HYDRATE_OBJECT);
        } catch (NoResultException|NonUniqueResultException $e) {
            return null;
        }
    }

    public function save(Country $country): void
    {
        $this->getEntityManager()->persist($country);
        $this->getEntityManager()->flush();
    }

    public function delete(Uuid $id): void
    {
        $this->getEntityManager()->remove($this->search($id));
        $this->getEntityManager()->flush();
    }

    //    public function searchByCriteria(Criteria $criteria): iterable
    //    {
    //        $qb = $this
    //            ->createQueryBuilder('p')
    //            ->orderBy('p.name');
    //
    //        foreach ($criteria->filters as $i => $filter) {
    //            if (Filter::EQUAL === $filter->operator) {
    //                $qb
    //                    ->andWhere(sprintf('p.%s = :value_%d', $filter->field, $i))
    //                    ->setParameter(sprintf('value_%d', $i), $filter->value);
    //
    //                continue;
    //            }
    //
    //            if (Filter::LIKE === $filter->operator) {
    //                $qb
    //                    ->andWhere(sprintf('p.%s LIKE :value_%d', $filter->field, $i))
    //                    ->setParameter(sprintf('value_%d', $i), sprintf('%%%s%%', $filter->value));
    //            }
    //        }
    //
    //        return $this->paginatedList($qb, null);
    //    }

    protected function getClass(): string
    {
        return Country::class;
    }
}
