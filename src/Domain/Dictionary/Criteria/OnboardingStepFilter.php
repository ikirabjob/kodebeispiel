<?php

namespace App\Domain\Dictionary\Criteria;

use App\Domain\Dictionary\QueryBuilder\NativeCondition;
use App\Domain\Dictionary\QueryBuilder\NativeFilter;

class OnboardingStepFilter extends NativeFilter
{
    public function __construct(string $value)
    {
        parent::__construct(
            [
                new NativeCondition('p.onboarding_step', self::EQUAL, $value),
            ]
        );
    }
}
