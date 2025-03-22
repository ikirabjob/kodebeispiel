<?php

namespace App\Domain\Person\Repository;

use App\Domain\Person\Exception\CountryNotFoundException;
use App\Domain\Person\Model\Country;
use App\Domain\Shared\Criteria\Criteria;
use Symfony\Component\Uid\Uuid;

interface CountryRepositoryInterface
{
    public function search(Uuid $id): ?Country;

    public function searchByLegacyId(int $legacyId): ?Country;

    public function save(Country $country): void;

    /**
     * @throws CountryNotFoundException
     */
    public function delete(Uuid $id): void;

    public function searchByCriteria(Criteria $criteria): iterable;
}
