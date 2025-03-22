<?php

namespace App\Domain\Person\Enums;

enum PersonStatusEnum: int
{
    case STATUS_CANDIDATE_NO_AGREEMENT = 0;
    case STATUS_ACKN_CANDIDATE = 1;
    case STATUS_VALIDATED_CANDIDATE = 2;

    case BLOCKED = 3;
}
