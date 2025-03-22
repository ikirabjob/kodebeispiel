<?php

namespace App\Domain\Dictionary\Criteria;

use App\Domain\Dictionary\QueryBuilder\NativeCondition;
use App\Domain\Dictionary\QueryBuilder\NativeFilter;

class LegacyIdFilter extends NativeFilter
{
    public function __construct(string $value)
    {
        parent::__construct(
            [
                new NativeCondition("(p.legacy_marker->>'platform_user_id')::int", self::EQUAL, $value),
            ]
        );
    }
}
