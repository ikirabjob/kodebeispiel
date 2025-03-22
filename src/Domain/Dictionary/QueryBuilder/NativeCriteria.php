<?php

namespace App\Domain\Dictionary\QueryBuilder;

final class NativeCriteria
{
    /**
     * @param iterable<NativeFilter> $nativeFilter
     */
    public function __construct(
        public iterable $nativeFilter
    ) {
    }
}
