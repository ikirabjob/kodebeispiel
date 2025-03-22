<?php

namespace App\Domain\Shared\ValueObject\Specification;

final class BusinessRuleValidationMessage
{
    public function __construct(
        public readonly string $message,
        public readonly int $code
    ) {
    }
}
