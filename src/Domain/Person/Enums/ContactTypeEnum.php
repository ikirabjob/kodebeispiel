<?php

namespace App\Domain\Person\Enums;

enum ContactTypeEnum: int
{
    case PERSONAL_EMAIL = 1;
    case OFFICE_EMAIL = 2;
    case PERSONAL_PHONE = 3;
    case REGISTRATION_ADDRESS = 4;
    case POSTAL_ADDRESS = 5;
}
