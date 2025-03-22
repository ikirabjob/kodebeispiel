<?php

namespace App\Domain\Person\Enums;

enum LoginTypeEnum: int
{
    case LOGIN_TYPE_LEGACY = 0;
    case LOGIN_TYPE_EMAIL = 1;
    case LOGIN_TYPE_PHONE_NUMBER = 2;
}
