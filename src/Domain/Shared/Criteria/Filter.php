<?php

namespace App\Domain\Shared\Criteria;

class Filter
{
    public const EQUAL = '=';
    public const IN = 'in';
    public const LIKE = 'LIKE';
    public const ILIKE = 'ILIKE';
    public const GREATER_THAN = '>=';
    public const LETTER_THAN = '<=';

    public function __construct(
        public string $field,
        public string $operator,
        public mixed $value,
    ) {
    }
}
