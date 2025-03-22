<?php

namespace App\Domain\Person\ValueObject\Auth;

class Credentials
{
    public function __construct(
        public readonly UserIdentifier $userIdentifier,
        public readonly HashedPassword $hashedPassword
    ) {
    }
}
