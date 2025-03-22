<?php

namespace App\Domain\Shared\Specification;

use App\Domain\Shared\Exception\BusinessRuleValidationException;
use App\Domain\Shared\Specification\Rule\BusinessRuleSpecificationInterface;

trait BusinessRuleCheckerTrait
{
    /**
     * @throws BusinessRuleValidationException
     */
    protected static function checkRule(BusinessRuleSpecificationInterface $businessRuleSpecification): void
    {
        if ($businessRuleSpecification->isSatisfiedBy()) {
            return;
        }

        throw new BusinessRuleValidationException($businessRuleSpecification);
    }
}
