<?php

namespace App\Application\Person\Command;

use App\Application\Shared\Command\CommandInterface;

class UpdateContactCommand implements CommandInterface
{
    public function __construct(
        public string $id,
        public ?array $content
    ) {
    }
}
