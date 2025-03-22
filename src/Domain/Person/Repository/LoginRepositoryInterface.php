<?php

namespace App\Domain\Person\Repository;

use App\Domain\Person\Exception\LoginNotFoundException;
use App\Domain\Person\Model\Login;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Uid\Uuid;

interface LoginRepositoryInterface
{
    /**
     * @throws LoginNotFoundException
     */
    public function search(Uuid $id): ?Login;

    /**
     * @throws LoginNotFoundException
     */
    public function searchByPersonId(Uuid $personId): Login;

    public function save(Login $login): void;

    /**
     * @throws LoginNotFoundException
     */
    public function delete(Uuid $id): void;

    /**
     * @throws NonUniqueResultException
     * @throws LoginNotFoundException
     */
    public function getByLoginName(string $loginName): Login;
}
