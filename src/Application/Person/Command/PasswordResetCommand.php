<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandInterface;

class PasswordResetCommand implements CommandInterface
{
    public function __construct(
        public string $loginName,
        public string $platform,
        public string $browser,
        public string $ip
    ) {
    }
}
