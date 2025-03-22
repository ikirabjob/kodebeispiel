<?php

namespace App\Domain\Shared\Exception;

use App\Domain\Shared\Specification\Rule\BusinessRuleSpecificationInterface;

final class BusinessRuleValidationException extends \Exception
{
    public function __construct(BusinessRuleSpecificationInterface $businessRuleSpecification)
    {
        $message = $businessRuleSpecification->validationMessage()->message;
        $code = $businessRuleSpecification->validationMessage()->code;

        parent::__construct($message, $code);
    }
}
