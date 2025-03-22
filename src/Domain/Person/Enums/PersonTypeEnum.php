<?php

namespace App\Domain\Person\Enums;

enum PersonTypeEnum: int
{
    case OLD = 1;
    case RESTORED = 2;
    case NEW = 3;
}
