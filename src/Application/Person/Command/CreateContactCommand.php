<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandInterface;

class CreateContactCommand implements CommandInterface
{
    public function __construct(
        public string $personId,
        public int $contactType,
        public ?array $content
    ) {
    }
}
