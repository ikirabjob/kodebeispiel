<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandInterface;

final class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public string $email,
        public string $parentPersonId
    ) {
    }
}
