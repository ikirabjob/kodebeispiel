<?php

namespace App\Domain\Person\Specification\Checker;

use App\Domain\Person\ValueObject\Auth\UserIdentifier;

interface LoginNameUniquenessCheckerInterface
{
    public function isUnique(UserIdentifier $userIdentifier): bool;
}
