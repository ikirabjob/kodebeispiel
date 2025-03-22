<?php

namespace App\Infrastructure\Person\Specification;

use App\Domain\Person\Exception\LoginNameAlreadyExistsException;
use App\Domain\Person\Repository\CheckLoginByUserIdentifierExistsInterface;
use App\Domain\Person\Specification\Checker\LoginNameUniquenessCheckerInterface;
use App\Domain\Person\ValueObject\Auth\UserIdentifier;
use Doctrine\ORM\NonUniqueResultException;

final class LoginNameUniquenessChecker implements LoginNameUniquenessCheckerInterface
{
    private CheckLoginByUserIdentifierExistsInterface $checkLoginByNameExists;

    public function __construct(CheckLoginByUserIdentifierExistsInterface $checkLoginByNameExists)
    {
        $this->checkLoginByNameExists = $checkLoginByNameExists;
    }

    public function isUnique(UserIdentifier $userIdentifier): bool
    {
        try {
            if ($this->checkLoginByNameExists->identifierExists($userIdentifier)) {
                throw new LoginNameAlreadyExistsException();
            }
        } catch (NonUniqueResultException $e) {
            throw new LoginNameAlreadyExistsException();
        }

        return true;
    }
}
