<?php

namespace App\Infrastructure\Person\Repository\Doctrine;

use App\Domain\Person\Exception\PersonNotFoundException;
use App\Domain\Person\Model\Person;
use App\Domain\Person\Repository\PersonRepositoryInterface;
use App\Infrastructure\Shared\Repository\AbstractRepository;
use App\Infrastructure\Shared\Repository\SearchByCriteriaTrait;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\Uid\Uuid;

class PersonRepository extends AbstractRepository implements PersonRepositoryInterface
{
    use SearchByCriteriaTrait;

    public function search(Uuid $id): ?Person
    {
        return $this->find($id);
    }

    public function save(Person $person): void
    {
        $this->getEntityManager()->persist($person);
        $this->getEntityManager()->flush();
    }

    public function delete(Uuid $id): void
    {
        $this->getEntityManager()->remove($this->search($id));
        $this->getEntityManager()->flush();
    }

    protected function getClass(): string
    {
        return Person::class;
    }

    public function getPersonByLegacyId(string $legacyUserId): ?Person
    {
        $builder = new ResultSetMappingBuilder($this->getEntityManager());
        $builder->addRootEntityFromClassMetadata($this->getClass(), 'p');
        $select = $builder->generateSelectClause(['p' => 'p']);

        $personQuery = $this->getEntityManager()
            ->createNativeQuery("select {$select} from person p where (p.legacy_marker->>'platform_user_id')::int = :legacyId", $builder)
            ->setParameters([
                ':legacyId' => $legacyUserId,
            ]);

        try {
            /* @var Person $person */
            return $personQuery->getSingleResult(AbstractQuery::HYDRATE_OBJECT);
        } catch (NoResultException|NonUniqueResultException $e) {
            return null;
        }
    }

    public function getConsultant(Uuid $personId): ?Person
    {
        $person = $this->search($personId);

        if (null === $person) {
            throw new PersonNotFoundException(sprintf('Person with id = "%s" not found', $personId->toRfc4122()));
        }

        return $person->getParentPerson();
    }

    public function getPersonDescendantsFirstLevel(Uuid $personId): array
    {
        try {
            $persons = $this->getEntityManager()
                ->getConnection()
                ->executeQuery("select
                                    p.person_id,
                                    p.first_name,
                                    p.last_name,
                                    p.personal_points, 
                                    p.structure_points,
                                    p.legacy_marker ->> 'platform_user_id' as legacy_id,
                                    l.level
                                from person p
                                left join level l on p.person_id = l.person_id
                                where p.parent_person_id = :personId
            ", ['personId' => $personId->toRfc4122()])
                ->fetchAllAssociative();
        } catch (Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }

        if (!$persons) {
            return [];
        }

        return $persons;
    }

    public function getPersonAncestors(Uuid $personId): array
    {
        try {
            $data = $this
                ->getEntityManager()
                ->getConnection()
                ->executeQuery('WITH RECURSIVE tree AS (SELECT person.person_id,
                               ARRAY []::uuid[]                             AS ancestors,
                               ARRAY [personal_points]::double precision[]  as personal_points,
                               ARRAY [structure_points]::double precision[] as structure_points,
                               ARRAY [l.level]::smallint[]                  as levels
                        FROM person
                                 left join level l on person.person_id = l.person_id
                        WHERE person.parent_person_id IS NULL

                        UNION ALL

                        SELECT person.person_id,
                               tree.ancestors || person.parent_person_id,
                               tree.personal_points || person.personal_points,
                               tree.structure_points || person.structure_points,
                               tree.levels || l.level
                        FROM person
                                 left join level l on person.person_id = l.person_id,
                             tree

                        WHERE person.parent_person_id = tree.person_id)
SELECT json_build_array(tree.ancestors)        as ancestors,
       json_build_array(tree.personal_points)  as personalPoints,
       json_build_array(tree.structure_points) as structurePoints,
       json_build_array(tree.levels)           as levels
FROM tree
where tree.person_id = :person_id', ['person_id' => $personId->toRfc4122()])
                ->fetchAllAssociative();
        } catch (Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }

        if (!$data) {
            return [];
        }

        $data = array_map(function ($row) {
            $aData = json_decode($row);

            return array_shift($aData);
        }, array_shift($data));

        return array_map(
            fn ($id, $pp, $sp, $lvl) => [
                'personId' => $id,
                'personalPoints' => $pp,
                'structurePoints' => $sp,
                'level' => $lvl,
            ],
            $data['ancestors'],
            array_slice($data['personalpoints'], 0, count($data['ancestors'])),
            array_slice($data['structurepoints'], 0, count($data['ancestors'])),
            array_slice($data['levels'], 0, count($data['ancestors']))
        );
    }

    public function getRootPerson(): ?Person
    {
        return $this->findOneBy(['parentPerson' => null]);
    }

    public function searchPersonsByFullName(string $personFullName): array
    {
    }
}
