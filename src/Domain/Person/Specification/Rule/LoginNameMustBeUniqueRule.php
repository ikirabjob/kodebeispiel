<?php

namespace App\Domain\Person\Specification\Rule;

use App\Domain\Person\Specification\Checker\LoginNameUniquenessCheckerInterface;
use App\Domain\Person\ValueObject\Auth\UserIdentifier;
use App\Domain\Shared\Specification\Rule\BusinessRuleSpecificationInterface;
use App\Domain\Shared\ValueObject\Specification\BusinessRuleValidationMessage;

final class LoginNameMustBeUniqueRule implements BusinessRuleSpecificationInterface
{
    public function __construct(
        private readonly LoginNameUniquenessCheckerInterface $loginNameUniquenessChecker,
        private readonly UserIdentifier $userIdentifier
    ) {
    }

    public function isSatisfiedBy(): bool
    {
        return $this->loginNameUniquenessChecker->isUnique($this->userIdentifier);
    }

    public function validationMessage(): BusinessRuleValidationMessage
    {
        return new BusinessRuleValidationMessage('Login with this name already exists', 001);
    }
}
