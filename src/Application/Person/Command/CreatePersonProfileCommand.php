<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandInterface;

class CreatePersonProfileCommand implements CommandInterface
{
    public function __construct(
        public string $personId
    ) {
    }
}
