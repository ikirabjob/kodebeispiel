<?php

namespace App\Domain\Person\Repository;

use App\Domain\Person\Exception\PersonNotFoundException;
use App\Domain\Person\Model\Person;
use App\Domain\Shared\Criteria\Criteria;
use Symfony\Component\Uid\Uuid;

interface PersonRepositoryInterface
{
    public function search(Uuid $id): ?Person;

    public function save(Person $person): void;

    /**
     * @throws PersonNotFoundException
     */
    public function delete(Uuid $id): void;

    public function getConsultant(Uuid $personId): ?Person;

    public function searchByCriteria(Criteria $criteria): iterable;

    public function getPersonAncestors(Uuid $personId): array;

    public function getPersonDescendantsFirstLevel(Uuid $personId): array;

    public function getRootPerson(): ?Person;

    public function searchPersonsByFullName(string $personFullName): array;
}
