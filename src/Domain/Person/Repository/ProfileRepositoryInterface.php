<?php

namespace App\Domain\Person\Repository;

use App\Domain\Person\Exception\PersonNotFoundException;
use App\Domain\Person\Model\Profile;
use App\Domain\Shared\Criteria\Criteria;
use Symfony\Component\Uid\Uuid;

interface ProfileRepositoryInterface
{
    public function search(Uuid $id): ?Profile;

    public function save(Profile $profile): void;

    /**
     * @throws PersonNotFoundException
     */
    public function delete(Uuid $id): void;

    public function searchByCriteria(Criteria $criteria): iterable;

    public function getProfileByPersonId(Uuid $personId): ?Profile;
}
