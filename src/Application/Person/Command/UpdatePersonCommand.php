<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandInterface;

final class UpdatePersonCommand implements CommandInterface
{
    public function __construct(
        public string $id,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $middleName,
    ) {
    }
}
