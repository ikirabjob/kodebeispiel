<?php

namespace App\Domain\Shared\Specification\Rule;

use App\Domain\Shared\ValueObject\Specification\BusinessRuleValidationMessage;

interface BusinessRuleSpecificationInterface
{
    public function isSatisfiedBy(): bool;

    public function validationMessage(): BusinessRuleValidationMessage;
}
