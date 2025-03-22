<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandInterface;

final class LoginChangeCommand implements CommandInterface
{
    public function __construct(
        public readonly string $personId,
        public readonly string $loginName,
        public readonly string $loginType
    ) {
    }
}
