<?php

namespace App\Domain\Person\Repository;

use App\Domain\Person\ValueObject\Auth\UserIdentifier;
use Doctrine\ORM\NonUniqueResultException;

interface CheckLoginByUserIdentifierExistsInterface
{
    /**
     * @throws NonUniqueResultException
     */
    public function identifierExists(UserIdentifier $userIdentifier): bool;
}
