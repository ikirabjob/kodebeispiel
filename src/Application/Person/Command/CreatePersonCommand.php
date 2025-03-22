<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandInterface;

final class CreatePersonCommand implements CommandInterface
{
    public function __construct(
        public string $parentPersonId,
        public string $firstName,
        public string $lastName,
        public string $middleName
    ) {
    }
}
